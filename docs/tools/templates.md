# Bundled templates

Four ready-made messages, each a mailable on `MailBase` with a view built from barua's components.

| Mailable | View | Subject | Data it uses |
|---|---|---|---|
| `Mail\Messages\Onboarding\WelcomeUser` | `messages.onboarding.welcome_user` | Welcome to :serviceName - Let's Get Started! | `name`, `companyName`, `productName`, `productOrService`, `solutionOrOffer`, `unsubscribeLink` |
| `Mail\Messages\Onboarding\VerifyEmail` | `messages.onboarding.email_verification` | Verify Your Email Address with :serviceName | `name`, `serviceName`, `verification_link` |
| `Mail\Messages\Onboarding\ForgotPassword` | `messages.onboarding.forgot_password` | Reset Your Password at :serviceName | `name`, `serviceName`, `reset_link` |
| `Mail\Messages\Marketing\PaymentConfirmation` | `messages.marketing.payment_confirmation` | Payment Confirmation for Your Order at :serviceName | `name`, `serviceName`, `invoice_id`, `invoice_total`, `download_link` |

Views are under `laranail/barua::`; the three shorter ones extend `messages.layout`, which also reads `companyName` and `unsubscribeLink`.

## Customising

Publish the views (`--tag=laranail::barua-views`) and edit `resources/views/vendor/laranail/barua/messages`. To add your own message, extend `MailBase` the way the bundled ones do, or use any Laravel mailable with `MailBuilder::setView($view, customPath: true)`.

## Planned

More templates were planned for the original package and are not built yet: order shipment, order placement, invoice, reminder, and feedback request.

---

[← Docs index](../../README.md#documentation)
