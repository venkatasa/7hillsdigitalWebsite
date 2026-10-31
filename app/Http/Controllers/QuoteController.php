<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use App\Support\Attribution;
use App\Support\Quote;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * /quote: project enquiries. The rules are /contact's: the lead is saved before
 * the notification is sent, it arrives `new`, and nothing advances it.
 */
class QuoteController extends Controller
{
    public function create()
    {
        abort_unless(Quote::enabled(), 404);

        $types = Quote::types();
        $type = request()->query('type');

        return view('pages.quote.create', [
            'types' => $types,
            'selected' => array_key_exists((string) $type, $types) ? $type : null,
            'from' => request()->query('from'),
        ]);
    }

    public function store(StoreQuoteRequest $request)
    {
        abort_unless(Quote::enabled(), 404);

        $data = $request->safe()->only([
            'project_type', 'message', 'budget_band', 'timeframe', 'name', 'company',
            'role', 'email', 'phone', 'company_size', 'preferred_contact',
        ]);
        $answers = $request->answers();

        $enquiry = Enquiry::create([
            ...$data,
            ...Attribution::forEnquiry($request),
            'journey' => 'project',
            'answers' => $answers,
            'priority' => Quote::priority($data['project_type'], $answers, $data['budget_band'], $data['timeframe']),
            'consent' => true,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 512),
            'status' => 'new',
        ]);

        // Saved first. A mail outage must never cost us the lead.
        try {
            Mail::to(config('brand.notify_email'))->send(new EnquiryReceived($enquiry));
        } catch (\Throwable $e) {
            Log::error('Enquiry notification failed', [
                'reference' => $enquiry->reference,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('quote.thanks')
            ->with('reference', $enquiry->reference)
            ->with('preferred', $enquiry->preferred_contact);
    }

    public function thanks()
    {
        abort_unless(Quote::enabled(), 404);

        return view('pages.quote.thanks', [
            'reference' => session('reference'),
            'preferred' => session('preferred'),
        ]);
    }
}
