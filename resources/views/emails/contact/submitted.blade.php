@component('mail::message')
# New Contact Message

You received a new message from the website.

**Name:** {{ $contact->name }}
**Email:** {{ $contact->email }}
**Subject:** {{ $contact->subject }}

**Message:**
{{ $contact->message }}

Thanks,
{{ config('app.name') }}
@endcomponent
