<h1>Hello {{ $contact->name }}</h1>,
<p>{!! $body !!}</p>

<img src="{{ route('track.email.open', ['contact' => $contact->id, 'list' => $contact->mailer_contact_id]) }}" width="1" height="1" style="display: none;" alt="tracker">
