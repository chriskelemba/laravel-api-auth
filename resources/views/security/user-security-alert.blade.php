@component('mail::message')
# Security Alert: {{ ucfirst(str_replace('_', ' ', $type)) }}

{{ $message }}

**Details of this activity:**
- Time: {{ $context['timestamp'] ?? now()->toDateTimeString() }}
- IP Address: {{ $context['ip'] ?? 'Unknown' }}
- Device: {{ $context['userAgent'] ?? 'Unknown' }}

@if($type === 'failed_login_attempt')
You have {{ $context['attempts_remaining'] }} attempts remaining before your account may be temporarily locked.
@endif

@component('mail::button', ['url' => route('security-settings')])
Review Security Settings
@endcomponent

If this wasn't you, please secure your account immediately.

Thanks,<br>
{{ config('app.name') }} Security Team
@endcomponent