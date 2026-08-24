<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Renders the social banner from the real brand assets.
 *
 * Built rather than prompted: an image generator cannot reproduce the 7Hills
 * lockup and mangles lettering, which on a brand page is worse than nothing.
 * This composites the actual logo file, the actual hex values from
 * config/brand.php and Montserrat at its real weights, so the banner cannot
 * drift from the site.
 */
class MakeBanner extends Command
{
    protected $signature = 'brand:banner
                            {--width=1640 : Output width in pixels}
                            {--height=624 : Output height in pixels}
                            {--headline= : Override the headline}
                            {--guides : Draw the Facebook safe-area guides}
                            {--out= : Output path}';

    protected $description = 'Render a social banner from config/brand.php and the brand artwork';

    private const FONTS = 'resources/fonts';

    public function handle(): int
    {
        $w = max(600, (int) $this->option('width'));
        $h = max(300, (int) $this->option('height'));
        $brand = config('brand');

        foreach (['Montserrat-ExtraBold', 'Montserrat-SemiBold', 'PlusJakartaSans-Medium'] as $f) {
            if (! File::exists(base_path(self::FONTS."/{$f}.ttf"))) {
                $this->error("Missing font {$f}.ttf — see ".self::FONTS.'/README.md');

                return self::FAILURE;
            }
        }

        $im = imagecreatetruecolor($w, $h);
        imagealphablending($im, true);
        imagesavealpha($im, true);

        $this->gradient($im, $w, $h, $brand['colors']['primary'], $brand['colors']['dark']);
        // Kept faint on purpose: the field has to stay Deep Forest, or the mint
        // headline loses its contrast against it.
        $this->glow($im, (int) ($w * 0.80), (int) ($h * 0.10), (int) ($h * 0.85), $brand['colors']['mint'], 0.070);
        $this->glow($im, (int) ($w * 0.12), (int) ($h * 1.05), (int) ($h * 0.75), $brand['colors']['accent'], 0.085);
        $this->bars($im, $w, $h, $brand['colors']['mint']);

        $this->compose($im, $w, $h, $brand);

        if ($this->option('guides')) {
            $this->guides($im, $w, $h);
        }

        $out = $this->option('out') ?: base_path('storage/app/brand/7hills-facebook-banner.png');
        File::ensureDirectoryExists(dirname($out));
        imagepng($im, $out, 6);
        imagedestroy($im);

        $this->info('Wrote '.$out);
        $this->line('  <fg=gray>'.$w.'×'.$h.', '.number_format(filesize($out) / 1024, 0).' KB</>');

        return self::SUCCESS;
    }

    /** Diagonal two-stop gradient, drawn as vertical bands. */
    private function gradient($im, int $w, int $h, string $from, string $to): void
    {
        [$r1, $g1, $b1] = $this->rgb($from);
        [$r2, $g2, $b2] = $this->rgb($to);

        for ($x = 0; $x < $w; $x++) {
            $t = $x / max(1, $w - 1);
            $c = imagecolorallocate($im,
                (int) round($r1 + ($r2 - $r1) * $t),
                (int) round($g1 + ($g2 - $g1) * $t),
                (int) round($b1 + ($b2 - $b1) * $t),
            );
            imagefilledrectangle($im, $x, 0, $x, $h, $c);
        }
    }

    /** Soft radial wash, built from concentric low-alpha discs. */
    private function glow($im, int $cx, int $cy, int $radius, string $hex, float $strength): void
    {
        [$r, $g, $b] = $this->rgb($hex);
        $steps = 160;

        for ($i = $steps; $i > 0; $i--) {
            $rad = (int) ($radius * ($i / $steps));
            $falloff = (1 - $i / $steps) ** 2;
            $alpha = (int) round(127 - ($falloff * $strength * 127));

            if ($alpha >= 127) {
                continue;
            }

            $c = imagecolorallocatealpha($im, $r, $g, $b, $alpha);
            imagefilledellipse($im, $cx, $cy, $rad * 2, $rad * 2, $c);
        }
    }

    /** The ascending bars from the logo mark, as a faint motif on the right. */
    private function bars($im, int $w, int $h, string $hex): void
    {
        [$r, $g, $b] = $this->rgb($hex);
        $baseY = (int) ($h * 0.92);
        $bw = (int) ($w * 0.030);
        $gap = (int) ($bw * 0.40);
        $x = (int) ($w * 0.795);

        foreach ([0.18, 0.30, 0.44, 0.60, 0.78] as $i => $frac) {
            $c = imagecolorallocatealpha($im, $r, $g, $b, 118 - $i * 3);
            imagefilledrectangle($im, $x, $baseY - (int) ($h * $frac), $x + $bw, $baseY, $c);
            $x += $bw + $gap;
        }
    }

    private function compose($im, int $w, int $h, array $brand): void
    {
        $white = imagecolorallocate($im, 255, 255, 255);
        [$mr, $mg, $mb] = $this->rgb($brand['colors']['mint']);
        $mint = imagecolorallocate($im, $mr, $mg, $mb);
        $muted = imagecolorallocatealpha($im, 255, 255, 255, 52);

        $cx = (int) ($w / 2);

        // Logo — the white cutout, so it sits on the dark field as supplied.
        $logoPath = public_path($brand['logo']['inverse']);

        if (File::exists($logoPath)) {
            $logo = imagecreatefrompng($logoPath);
            $lh = (int) ($h * 0.20);
            $lw = (int) (imagesx($logo) * ($lh / imagesy($logo)));
            imagealphablending($im, true);
            imagecopyresampled($im, $logo, $cx - (int) ($lw / 2), (int) ($h * 0.07), 0, 0, $lw, $lh, imagesx($logo), imagesy($logo));
            imagedestroy($logo);
        }

        // Everything stays inside the width a phone will still show.
        $safe = (int) ($w * 0.72);

        $headline = $this->option('headline') ?: 'Pay nothing until your website is live.';

        $this->centred($im, $cx, (int) ($h * 0.545), (int) ($h * 0.082), 'Montserrat-ExtraBold', $headline, $white, $safe);
        $this->centred($im, $cx, (int) ($h * 0.655), (int) ($h * 0.082), 'Montserrat-ExtraBold', 'Cancel any time.', $mint, $safe);
        $this->centred($im, $cx, (int) ($h * 0.775), (int) ($h * 0.040), 'PlusJakartaSans-Medium', $brand['tagline'], $muted, $safe);

        $contact = $brand['contact']['phone'].'   ·   7hillsdigital.co.za';
        $this->centred($im, $cx, (int) ($h * 0.885), (int) ($h * 0.036), 'Montserrat-SemiBold', $contact, $muted, $safe);
    }

    /**
     * Facebook crops the sides on mobile, so text is shrunk until it fits the
     * safe width rather than being allowed to run off the edge of a phone.
     */
    private function centred($im, int $cx, int $baseline, int $size, string $font, string $text, int $colour, ?int $maxWidth = null): void
    {
        $path = base_path(self::FONTS."/{$font}.ttf");

        do {
            $box = imagettfbbox($size, 0, $path, $text);
            $width = $box[2] - $box[0];

            if ($maxWidth === null || $width <= $maxWidth || $size <= 8) {
                break;
            }

            $size--;
        } while (true);

        imagettftext($im, $size, 0, $cx - (int) ($width / 2), $baseline, $colour, $path, $text);
    }

    /**
     * Facebook crops the sides on mobile and covers the bottom-left with the
     * profile picture. These are drawn only with --guides, to check a layout.
     */
    private function guides($im, int $w, int $h): void
    {
        $red = imagecolorallocatealpha($im, 255, 80, 80, 60);
        $safeX = (int) ($w * 0.16);
        imagerectangle($im, $safeX, 8, $w - $safeX, $h - 8, $red);
        imagerectangle($im, 0, (int) ($h * 0.62), (int) ($w * 0.22), $h - 1, $red);
    }

    /** @return array{int,int,int} */
    private function rgb(string $hex): array
    {
        $hex = ltrim($hex, '#');

        return [
            (int) hexdec(substr($hex, 0, 2)),
            (int) hexdec(substr($hex, 2, 2)),
            (int) hexdec(substr($hex, 4, 2)),
        ];
    }
}
