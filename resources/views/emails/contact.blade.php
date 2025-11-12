@component('mail::message')
# {{ __('contact.title') }}

**{{ __('contact.from') }}:** {{ $data['name'] }} ({{ $data['email'] }})

{{ $data['message'] }}

@endcomponent
