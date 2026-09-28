@extends('laranail/barua::messages.layout')

@section('preheader', 'Your payment was received.')

@section('content')
    <x-laranail-barua::heading as="h1" style="font-size: 24px; color: #1a1f36;">Payment received</x-laranail-barua::heading>
    <x-laranail-barua::text>Hi {{ $name ?? 'there' }},</x-laranail-barua::text>
    <x-laranail-barua::text>
        Thank you for your order with {{ $serviceName ?? config('app.name') }}. We have received your payment.
    </x-laranail-barua::text>
    <x-laranail-barua::row style="border: 1px solid #e6ebf1; border-radius: 6px;">
        <x-laranail-barua::td style="padding: 12px 16px; color: #525f7f;">Invoice</x-laranail-barua::td>
        <x-laranail-barua::td style="padding: 12px 16px; text-align: right; font-weight: 600;">{{ $invoice_id ?? '' }}</x-laranail-barua::td>
    </x-laranail-barua::row>
    <x-laranail-barua::row style="border: 1px solid #e6ebf1; border-radius: 6px;">
        <x-laranail-barua::td style="padding: 12px 16px; color: #525f7f;">Total</x-laranail-barua::td>
        <x-laranail-barua::td style="padding: 12px 16px; text-align: right; font-weight: 600;">{{ $invoice_total ?? '' }}</x-laranail-barua::td>
    </x-laranail-barua::row>
    @isset($download_link)
        <x-laranail-barua::text>
            <x-laranail-barua::link href="{{ $download_link }}">Download your invoice</x-laranail-barua::link>
        </x-laranail-barua::text>
    @endisset
@endsection
