<x-mail::message>
# New enquiry: {{ $enquiry->reference }}

**{{ $enquiry->name }}**@if ($enquiry->company) — {{ $enquiry->company }}@endif

- **Interested in:** {{ $enquiry->interest }}
- **Email:** {{ $enquiry->email }}
@if ($enquiry->phone)
- **Phone:** {{ $enquiry->phone }}
@endif
@if ($enquiry->industry)
- **Industry:** {{ $enquiry->industry }}
@endif
@if ($enquiry->current_website)
- **Current website:** {{ $enquiry->current_website }}
@endif
- **Received:** {{ $enquiry->created_at->format('d/m/Y H:i') }}

@if ($enquiry->message)
**What they said**

> {{ $enquiry->message }}
@endif

<x-mail::button :url="$url">
Open in the back office
</x-mail::button>

Reply to this email and it goes straight to {{ $enquiry->name }}.
</x-mail::message>
