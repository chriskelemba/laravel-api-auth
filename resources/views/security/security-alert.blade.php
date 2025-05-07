@component('mail::message')
# Security Alert: {{ $subject }}

{{ $message }}

**Details:**
- Time: {{ $context['timestamp'] ?? now()->toDateTimeString() }}
- IP Address: {{ $context['ip'] ?? 'Unknown' }}
- User Agent: {{ $context['userAgent'] ?? 'Unknown' }}

@if(isset($context['attempted_route']))
- Attempted Route: {{ $context['attempted_route'] }}
@endif

@component('mail::button', ['url' => url('/security')])
View Security Dashboard
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent