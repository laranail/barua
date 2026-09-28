@extends('laranail/barua::messages.layout')

@section('preheader', 'Use this link to choose a new password.')

@section('content')
    <x-laranail-barua::heading as="h1" style="font-size: 24px; color: #1a1f36;">Reset your password</x-laranail-barua::heading>
    <x-laranail-barua::text>Hi {{ $name ?? 'there' }},</x-laranail-barua::text>
    <x-laranail-barua::text>
        We received a request to reset the password for your {{ $serviceName ?? config('app.name') }} account. Choose a new one with the button below.
    </x-laranail-barua::text>
    <x-laranail-barua::link href="{{ $reset_link ?? '#' }}" style="display: inline-block; background-color: #067df7; color: #ffffff; padding: 12px 20px; border-radius: 6px; font-weight: 600;">
        Choose a new password
    </x-laranail-barua::link>
    <x-laranail-barua::text style="font-size: 13px; color: #525f7f;">
        If you did not ask for this, ignore this message: your password stays as it is.
    </x-laranail-barua::text>
@endsection
