# Send a bundled template

Send the password-reset message to a user.

```php
use Simtabi\Laranail\Barua\Builders\DataBuilder;
use Simtabi\Laranail\Barua\Builders\MailBuilder;
use Simtabi\Laranail\Barua\Services\MailSender;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;
use Simtabi\Laranail\Barua\Mail\Messages\Onboarding\ForgotPassword;

$errors  = new ErrorBuilder();
$data    = (new DataBuilder())->setData([
    'name'        => $user->name,
    'serviceName' => config('app.name'),
    'reset_link'  => $resetUrl,
]);
$builder = (new MailBuilder(errorBuilder: $errors))->setTo($user->email, $user->name);

$mail = new ForgotPassword(mailBuilder: $builder, dataBuilder: $data, errorBuilder: $errors);

(new MailSender(mailable: $mail, mailBuilder: $builder, dataBuilder: $data, errorBuilder: $errors))->sendEmail();
```

The data each template reads is in [Bundled templates](../tools/templates.md).

---

[← Docs index](../../README.md#documentation)
