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
@if ($enquiry->journey === 'project')
- **Priority:** {{ \App\Support\Quote::priorityLabel($enquiry->priority) }}
@if ($enquiry->role)
- **Role:** {{ $enquiry->role }}
@endif

**About the work**

@foreach ($answers as $question => $answer)
- **{{ $question }}:** {{ $answer }}
@endforeach
@endif

@if ($enquiry->message)
**What they said**

> {{ $enquiry->message }}
@endif

<x-mail::button :url="$url">
Open in the back office
</x-mail::button>

Reply to this email and it goes straight to {{ $enquiry->name }}.
</x-mail::message>
