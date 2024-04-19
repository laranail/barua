

<table align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" {{ $attributes }}>
    <tbody style="width: 100%;">
        <tr {!! $styling !!} {{ $attributes->merge(['style' => 'width: 100%;']) }}>
            {{ $slot }}
        </tr>
    </tbody>
</table>
