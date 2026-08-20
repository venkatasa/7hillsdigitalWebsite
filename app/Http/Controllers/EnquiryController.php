<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnquiryRequest;
use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use App\Models\Package;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnquiryController extends Controller
{
    public function create()
    {
        return view('pages.contact', [
            'packages' => Package::published()->with('tiers')->orderBy('sort')->get(),
            // Deep link from a package page: /contact?package=showroom
            'selected' => Package::published()
                ->where('slug', request()->query('package'))
                ->first(),
        ]);
    }

    public function store(StoreEnquiryRequest $request)
    {
        $enquiry = Enquiry::create([
            ...$request->safe()->except('website'),
            'consent' => true,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 512),
            'status' => 'new',
        ]);

        /*
         | The lead is saved before anything is sent. A mail outage must never
         | cost us an enquiry — we would rather deliver it late from /admin
         | than lose it at the door.
         */
        try {
            Mail::to(config('brand.notify_email'))->send(new EnquiryReceived($enquiry));
        } catch (\Throwable $e) {
            Log::error('Enquiry notification failed', [
                'reference' => $enquiry->reference,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('contact.thanks')
            ->with('reference', $enquiry->reference);
    }

    public function thanks()
    {
        return view('pages.thank-you', [
            'reference' => session('reference'),
        ]);
    }
}
