@extends('laranail/barua::messages.layout')

@section('preheader', 'Confirm your email address to finish setting up your account.')

@section('content')
    <x-laranail-barua::heading as="h1" style="font-size: 24px; color: #1a1f36;">Verify your email address</x-laranail-barua::heading>
    <x-laranail-barua::text>Hi {{ $name ?? 'there' }},</x-laranail-barua::text>
    <x-laranail-barua::text>
        Thanks for signing up for {{ $serviceName ?? config('app.name') }}. Confirm this is your email address to finish setting up your account.
    </x-laranail-barua::text>
    <x-laranail-barua::link href="{{ $verification_link ?? '#' }}" style="display: inline-block; background-color: #067df7; color: #ffffff; padding: 12px 20px; border-radius: 6px; font-weight: 600;">
        Verify email address
    </x-laranail-barua::link>
    <x-laranail-barua::text style="font-size: 13px; color: #525f7f;">
        If you did not create an account, you can ignore this message.
    </x-laranail-barua::text>
@endsection
