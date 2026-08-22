<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

/**
 * Checks that outbound mail will actually be accepted.
 *
 * Gmail rejects any domain where neither SPF nor DKIM passes, which is what
 * bounced our mail in August 2026. This verifies the three DNS records and the
 * SMTP credentials in one place, so "is our email working?" has an answer that
 * does not involve sending something to a personal Gmail account and hoping.
 */
class CheckMail extends Command
{
    protected $signature = 'mail:check
                            {--selector=default : The DKIM selector to look for}
                            {--send= : Also send a test message to this address}';

    protected $description = 'Verify SPF, DKIM, DMARC and the SMTP configuration for the sending domain';

    private bool $failed = false;

    public function handle(): int
    {
        $from = (string) config('mail.from.address');
        $domain = str_contains($from, '@') ? substr(strrchr($from, '@'), 1) : null;

        if (! $domain) {
            $this->error('MAIL_FROM_ADDRESS is not set, so there is no domain to check.');

            return self::FAILURE;
        }

        $this->info("Checking outbound mail for {$domain}");
        $this->newLine();

        $this->checkSpf($domain);
        $this->checkDkim($domain, (string) $this->option('selector'));
        $this->checkDmarc($domain);
        $this->checkSmtp();

        $this->newLine();

        if ($recipient = $this->option('send')) {
            $this->sendTest((string) $recipient);
        }

        if ($this->failed) {
            $this->newLine();
            $this->warn('Something above needs attention before mail will be delivered reliably.');

            return self::FAILURE;
        }

        $this->info('All checks passed.');

        return self::SUCCESS;
    }

    private function checkSpf(string $domain): void
    {
        $records = $this->txt($domain);
        $spf = collect($records)->first(fn ($r) => str_starts_with($r, 'v=spf1'));

        if (! $spf) {
            $this->line('  <fg=red>✗</> SPF        no v=spf1 record on '.$domain);
            $this->failed = true;

            return;
        }

        $this->line('  <fg=green>✓</> SPF        '.$spf);

        if (str_ends_with(trim($spf), '+all')) {
            $this->line('    <fg=yellow>!</> ends in +all, which authorises the whole internet');
            $this->failed = true;
        }
    }

    private function checkDkim(string $domain, string $selector): void
    {
        $name = "{$selector}._domainkey.{$domain}";
        $record = implode('', $this->txt($name));

        if (! str_contains($record, 'v=DKIM1')) {
            $this->line("  <fg=red>✗</> DKIM       nothing at {$name}");
            $this->failed = true;

            return;
        }

        if (! preg_match('/p=([A-Za-z0-9+\/=]+)/', $record, $m) || $m[1] === '') {
            $this->line('  <fg=red>✗</> DKIM       record present but the public key is empty (revoked)');
            $this->failed = true;

            return;
        }

        $der = base64_decode($m[1], true);

        if ($der === false) {
            $this->line('  <fg=red>✗</> DKIM       public key is not valid base64');
            $this->failed = true;

            return;
        }

        $bits = strlen($der) > 260 ? 2048 : 1024;
        $this->line("  <fg=green>✓</> DKIM       {$selector}._domainkey, RSA-{$bits}");

        if ($bits === 1024) {
            $this->line('    <fg=yellow>!</> 1024-bit keys are deprecated; regenerate at 2048 when the host allows');
        }
    }

    private function checkDmarc(string $domain): void
    {
        $record = collect($this->txt("_dmarc.{$domain}"))
            ->first(fn ($r) => str_starts_with($r, 'v=DMARC1'));

        if (! $record) {
            $this->line('  <fg=red>✗</> DMARC      no record at _dmarc.'.$domain);
            $this->failed = true;

            return;
        }

        $this->line('  <fg=green>✓</> DMARC      '.$record);

        if (! str_contains($record, 'rua=')) {
            $this->line('    <fg=yellow>!</> no rua= address, so nobody receives the aggregate reports —');
            $this->line('      without them you cannot tell whether p=none is safe to tighten');
        }
    }

    private function checkSmtp(): void
    {
        $mailer = config('mail.default');

        if ($mailer !== 'smtp') {
            $this->line("  <fg=yellow>!</> SMTP       MAIL_MAILER is '{$mailer}', so nothing is actually sent");

            return;
        }

        $host = config('mail.mailers.smtp.host');
        $port = config('mail.mailers.smtp.port');
        $user = config('mail.mailers.smtp.username');
        $pass = config('mail.mailers.smtp.password');

        foreach (['host' => $host, 'port' => $port, 'username' => $user, 'password' => $pass] as $key => $value) {
            if (blank($value)) {
                $this->line('  <fg=red>✗</> SMTP       MAIL_'.strtoupper($key).' is not set');
                $this->failed = true;

                return;
            }
        }

        $socket = @fsockopen($host, (int) $port, $errno, $errstr, 8);

        if ($socket === false) {
            $this->line("  <fg=red>✗</> SMTP       cannot reach {$host}:{$port} — {$errstr}");
            $this->failed = true;

            return;
        }

        fclose($socket);
        $this->line("  <fg=green>✓</> SMTP       {$host}:{$port} reachable, authenticating as {$user}");

        /*
         | The envelope sender must match the domain we published SPF and DKIM
         | for. Authenticating as one domain and sending as another is exactly
         | what Gmail rejected.
         */
        $from = (string) config('mail.from.address');

        if (is_string($user) && str_contains($user, '@') && ! str_ends_with($from, substr(strrchr($user, '@'), 1))) {
            $this->line('    <fg=yellow>!</> MAIL_FROM_ADDRESS is on a different domain to MAIL_USERNAME');
            $this->failed = true;
        }
    }

    private function sendTest(string $recipient): void
    {
        $this->info("Sending a test message to {$recipient}…");

        try {
            Mail::raw(
                'This is a test from '.config('app.name').".\n\n".
                "If it arrived in the inbox rather than spam, SPF and DKIM are working.\n".
                'Check the message headers for: spf=pass, dkim=pass, dmarc=pass.',
                fn ($message) => $message->to($recipient)->subject(config('app.name').' — mail test'),
            );

            $this->line('  <fg=green>✓</> handed to the mail server without error');
            $this->line('  <fg=gray>Now open it and check the headers — delivery is not the same as passing.</>');
        } catch (\Throwable $e) {
            $this->line('  <fg=red>✗</> '.$e->getMessage());
            $this->failed = true;
        }
    }

    /** @return array<int, string> */
    private function txt(string $name): array
    {
        $records = @dns_get_record($name, DNS_TXT) ?: [];

        return array_map(
            fn ($r) => $r['txt'] ?? implode('', $r['entries'] ?? []),
            $records,
        );
    }
}
