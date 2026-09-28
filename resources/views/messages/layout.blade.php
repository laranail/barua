{{--
    Shared layout for barua's bundled messages, built from barua's own components.
    Sections: `preheader` (optional), `content`. Variables: $companyName, $unsubscribeLink.
--}}
<x-laranail-barua::html lang="en">
    <x-laranail-barua::head>
        <title>{{ $companyName ?? config('app.name') }}</title>
    </x-laranail-barua::head>
    <x-laranail-barua::body style="background-color: #f6f9fc; margin: 0; padding: 24px 0;">
        @hasSection('preheader')
            <div style="display: none; max-height: 0; overflow: hidden;">@yield('preheader')</div>
        @endif
        <x-laranail-barua::container style="background-color: #ffffff; border-radius: 8px; padding: 32px;">
            <x-laranail-barua::section>
                @yield('content')
            </x-laranail-barua::section>
            <x-laranail-barua::hr style="margin: 32px 0 16px;" />
            <x-laranail-barua::section>
                <x-laranail-barua::text style="font-size: 12px; line-height: 18px; color: #8898aa; margin: 0;">
                    &copy; {{ date('Y') }} {{ $companyName ?? config('app.name') }}.
                    @isset($unsubscribeLink)
                        <x-laranail-barua::link href="{{ $unsubscribeLink }}" style="color: #8898aa; text-decoration: underline;">Unsubscribe</x-laranail-barua::link>
                    @endisset
                </x-laranail-barua::text>
            </x-laranail-barua::section>
        </x-laranail-barua::container>
    </x-laranail-barua::body>
</x-laranail-barua::html>
