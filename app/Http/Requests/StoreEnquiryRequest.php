<?php

namespace App\Http\Requests;

use App\Models\PackageTier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'company' => ['nullable', 'string', 'max:150'],
            // rfc only, not dns: a DNS hiccup must never cost us a lead at the door.
            'email' => ['required', 'email:rfc', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],

            'package_id' => ['nullable', Rule::exists('packages', 'id')->whereNull('deleted_at')],
            'package_tier_id' => ['nullable', Rule::exists('package_tiers', 'id')],

            'industry' => ['nullable', 'string', 'max:120'],
            'current_website' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:4000'],

            // POPIA: consent is explicit and must be given, not assumed.
            'consent' => ['accepted'],

            // Honeypot. Real people never see this field, so anything in it is a bot.
            'website' => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'consent.accepted' => 'Please tick the box so we may use your details to answer you.',
            'website.prohibited' => 'That submission looked automated. Please try again.',
        ];
    }

    /**
     * A tier must actually belong to the package that was chosen, or a crafted
     * post could pair "Business" with the Showroom price.
     */
    public function after(): array
    {
        return [
            function ($validator) {
                $tierId = $this->input('package_tier_id');
                $packageId = $this->input('package_id');

                if (! $tierId || ! $packageId) {
                    return;
                }

                $belongs = PackageTier::whereKey($tierId)
                    ->where('package_id', $packageId)
                    ->exists();

                if (! $belongs) {
                    $validator->errors()->add('package_tier_id', 'That package and tier do not go together.');
                }
            },
        ];
    }
}
