@component('mail::message')
{{-- Greeting --}}
# @lang('Hello!')

{{-- Intro Lines --}}
{{ $introLines }}

{{-- Action Button --}}
@isset($actionText)
@component('mail::button', ['url' => $actionUrl, 'color' => "primary"])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
{{ $outroLines }}

{{-- Salutation --}}
@lang('Regards'),<br>
{{ config('app.name') }}

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
@lang(
    "If you’re having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
    'into your web browser:',
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
@endslot
@endisset
@endcomponent