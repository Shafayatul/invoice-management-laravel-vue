@component('mail::message')
{{-- Greeting --}}
# @lang('Hello!')

{{-- Intro Lines --}}
@foreach ($lines as $line)
{{ $line }}
@endforeach

{{-- Salutation --}}
@lang('Regards'),<br>
{{ config('app.name') }}
@endcomponent
