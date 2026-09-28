# Build a custom email

Write your own view with the components and send it through the builder.

```blade
{{-- resources/views/emails/shipped.blade.php --}}
<x-laranail-barua::html>
    <x-laranail-barua::body>
        <x-laranail-barua::container>
            <x-laranail-barua::heading>Your order has shipped</x-laranail-barua::heading>
            <x-laranail-barua::text>Order {{ $orderId }} is on its way.</x-laranail-barua::text>
        </x-laranail-barua::container>
    </x-laranail-barua::body>
</x-laranail-barua::html>
```

```php
$builder = (new MailBuilder(errorBuilder: $errors))
    ->setTo($user->email)
    ->setSubject('Order :orderId shipped')
    ->setView('emails.shipped', customPath: true);

$data = (new DataBuilder())->setData(['orderId' => $order->id]);

(new MailSender(mailable: new Illuminate\Mail\Mailable(), mailBuilder: $builder, dataBuilder: $data, errorBuilder: $errors))
    ->sendEmail();
```

Every component and its props: [Components](../tools/components.md).

---

[← Docs index](../../README.md#documentation)
