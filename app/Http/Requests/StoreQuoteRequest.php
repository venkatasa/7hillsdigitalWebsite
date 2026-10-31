<?php

namespace App\Http\Requests;

use App\Support\Quote;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * A project enquiry from /quote. The same rules as /contact (rfc-only email,
 * explicit consent, honeypot) plus: the type must be one we may offer here,
 * and every answer must come from that type's own lists, so a crafted post
 * cannot store a gated type or a value the form never showed.
 */
class StoreQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Before validation, so a switched-off /quote is a 404 and not a 422.
        abort_unless(Quote::enabled(), 404);

        return true;
    }

    public function rules(): array
    {
        $type = (string) $this->input('project_type');
        $def = array_key_exists($type, Quote::types()) ? Quote::type($type) : null;

        $rules = [
            'project_type' => ['required', Rule::in(array_keys(Quote::types()))],
            'message' => [($def['message'][1] ?? true) ? 'required' : 'nullable', 'string', 'max:4000'],
            'budget_band' => ['required', Rule::in(array_keys(Quote::budgets($type)))],
            'timeframe' => ['required', Rule::in(array_keys(config('quote.timeframes')))],

            'name' => ['required', 'string', 'max:120'],
            'company' => ['nullable', 'string', 'max:150'],
            'role' => ['nullable', 'string', 'max:80'],
            // rfc only, not dns: a DNS hiccup must never cost us a lead at the door.
            'email' => ['required', 'email:rfc', 'max:180'],
            'phone' => ['required', 'string', 'max:40'],
            'company_size' => ['required', Rule::in(array_keys(config('quote.company_sizes')))],
            'preferred_contact' => ['required', Rule::in(array_keys(config('quote.preferred_contact')))],

            'from' => ['nullable', 'string', 'max:60'],

            // POPIA: consent is explicit and must be given, not assumed.
            'consent' => ['accepted'],

            // Honeypot. Real people never see this field, so anything in it is a bot.
            'website' => ['prohibited'],
        ];

        // Only the chosen type's answers are read; the other types' are ignored.
        foreach ($def['questions'] ?? [] as $id => [, $options]) {
            $rules["answers.{$type}.{$id}"] = ['required', Rule::in(array_keys($options))];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'project_type.required' => 'Please choose what you need, or "Something else".',
            'project_type.in' => 'Please choose what you need, or "Something else".',
            'message.required' => 'Please tell us a little about the work.',
            'budget_band.required' => 'Please choose a budget, or "Not sure yet".',
            'budget_band.in' => 'Please choose a budget, or "Not sure yet".',
            'answers.*.*.required' => 'Please answer each question about the work.',
            'timeframe.required' => 'Please choose when you would like to start.',
            'timeframe.in' => 'Please choose when you would like to start.',
            'company_size.required' => 'Please choose your company size.',
            'company_size.in' => 'Please choose your company size.',
            'consent.accepted' => 'Please tick the box so we may use your details to answer you.',
            'website.prohibited' => 'That submission looked automated. Please try again.',
        ];
    }

    public function attributes(): array
    {
        return [
            'preferred_contact' => 'how we should contact you',
        ];
    }

    /** The chosen type's answers only, as [question => value]. */
    public function answers(): array
    {
        $type = $this->validated('project_type');

        return array_intersect_key(
            (array) $this->input("answers.{$type}", []),
            Quote::type($type)['questions'] ?? [],
        );
    }
}
