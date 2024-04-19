
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <!-- Compiled with Bootstrap Email version: 1.3.1 -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <style type="text/css">
        body,table,td{font-family:Helvetica,Arial,sans-serif !important}.ExternalClass{width:100%}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div{line-height:150%}a{text-decoration:none}*{color:inherit}a[x-apple-data-detectors],u+#body a,#MessageViewBody a{color:inherit;text-decoration:none;font-size:inherit;font-family:inherit;font-weight:inherit;line-height:inherit}img{-ms-interpolation-mode:bicubic}table:not([class^=s-]){font-family:Helvetica,Arial,sans-serif;mso-table-lspace:0pt;mso-table-rspace:0pt;border-spacing:0px;border-collapse:collapse}table:not([class^=s-]) td{border-spacing:0px;border-collapse:collapse}@media screen and (max-width: 600px){.w-full,.w-full>tbody>tr>td{width:100% !important}.w-24,.w-24>tbody>tr>td{width:96px !important}.w-40,.w-40>tbody>tr>td{width:160px !important}.p-lg-10:not(table),.p-lg-10:not(.btn)>tbody>tr>td,.p-lg-10.btn td a{padding:0 !important}.p-3:not(table),.p-3:not(.btn)>tbody>tr>td,.p-3.btn td a{padding:12px !important}.p-6:not(table),.p-6:not(.btn)>tbody>tr>td,.p-6.btn td a{padding:24px !important}*[class*=s-lg-]>tbody>tr>td{font-size:0 !important;line-height:0 !important;height:0 !important}.s-4>tbody>tr>td{font-size:16px !important;line-height:16px !important;height:16px !important}.s-6>tbody>tr>td{font-size:24px !important;line-height:24px !important;height:24px !important}.s-10>tbody>tr>td{font-size:40px !important;line-height:40px !important;height:40px !important}}
    </style>
    <style type="text/css">
        /* General CSS normalisation - investigating common CSS seen in emails */

        /*
        Normalise on all email clients
        Apple Mail, iOS Mail plus many more have preset margin and padding for the email body - this normalises it so rendering is consistent and designers can choose.
        */

        body {
            margin: 0;
            padding: 0;
        }

        /*
        Fix for Outlook on Windows
        border-collapse to stop spaces between tables caused by border size
        mso-table-lspace / mso-table-rspace to ensure no left and right space is added next to tables - Outlook specific CSS attributes
         */

        table {
            border-collapse:collapse;
            mso-table-lspace:0;
            mso-table-rspace:0;
        }

        /*
        Older versions of Samsung mail reset the font-size on <h1>-<h6> elements - But the newer versions don’t.
        Mail.ru resets font-size on <h1> & <h3> but other <h*> are left
        outlook.com resets margin on an <h3> but others are left
        So I think a “normalise” on <h1>-<h3> would make sense
        */

        h1 {
            margin:0.67em 0;
            font-size:2em;
        }
        h2 {
            margin:0.83em 0;
            font-size:1.5em;
        }

        /* html[dir] h3 is to increase specificity to override Outlook.com */
        html[dir] h3, h3 {
            margin:1em 0;
            font-size:1.17em;
        }

        /* From here - all CSS normalisation is based on a specific email client situation */

        /* Fix for Outlook links color fix for links and visited links */

        span.MsoHyperlink {
            color: inherit !important;
            mso-style-priority: 99 !important;
        }

        span.MsoHyperlinkFollowed {
            color: inherit !important;
            mso-style-priority: 99 !important;
        }

        /* normalise link attributes in Apple Mail / iOS Mail apps - to match the parent element */

        #root [x-apple-data-detectors=true],
        a[x-apple-data-detectors=true]{
            color: inherit !important;
            text-decoration: inherit !important;
        }

        /* normalise link attributes in Apple Mail / iOS Mail apps for the date calendar event- to match the parent element */
        [x-apple-data-detectors-type="calendar-event"] {
            color: inherit !important;
            -webkit-text-decoration-color: inherit !important;
        }

        /* normalise link attributes in Gmail - to match the parent element. NOTE: Need to add class="body" to the body element and a DOCTYPE must be present. */

        u + .body a {
            color: inherit;
            text-decoration: none;
            font-size: inherit;
            font-weight: inherit;
            line-height: inherit;
        }

        /* Mark Robbins found iOS Gmail will add word-spacing: 1px and word-wrap: break-word
        https://github.com/JayOram/email-css-resets/issues/2#issue-805476023
        so added the below to fix that

        This doesn't fix GANGA - so may need to be added inline -
        <body style="word-wrap: normal; word-spacing:normal;">
        */

        .body {
            word-wrap: normal;
            word-spacing:normal;
        }

        /* centre email on Android 4.4 - margin reset */

        div[style*="margin: 16px 0"] {
            margin: 0!important;
        }

        /* revert all styles for LaPoste webmail */

        #message *{
            all:revert
        }

        /* outlook.com 'celebrations' highlight removal - this doesn't stop the animation, just the highlight and color */

        [data-markjs]{
            color:inherit;
            padding:0;
            background:none;
        }
    </style>
</head>
<body class="bg-light" style="outline: 0; width: 100%; min-width: 100%; height: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; font-family: Helvetica, Arial, sans-serif; line-height: 24px; font-weight: normal; font-size: 16px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; color: #000000; margin: 0; padding: 0; border-width: 0;" bgcolor="#FAF7F2">
<table class="bg-light body" valign="top" role="presentation" border="0" cellpadding="0" cellspacing="0" style="outline: 0; width: 100%; min-width: 100%; height: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; font-family: Helvetica, Arial, sans-serif; line-height: 24px; font-weight: normal; font-size: 16px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; color: #000000; margin: 0; padding: 0; border-width: 0;" bgcolor="#FAF7F2">
    <tbody>
    <tr>
        <td valign="top" style="line-height: 24px; font-size: 16px; margin: 0;" align="left" bgcolor="#FAF7F2">
            <table class="container" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                <tbody>
                <tr>
                    <td align="center" style="line-height: 24px; font-size: 16px; margin: 0; padding: 0 16px;">
                        <!--[if (gte mso 9)|(IE)]>
                        <table align="center" role="presentation">
                            <tbody>
                            <tr>
                                <td width="600">
                        <![endif]-->
                        <table align="center" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 600px; margin: 0 auto;">
                            <tbody>
                            <tr>
                                <td style="line-height: 24px; font-size: 16px; margin: 0;" align="left">
                                    <table class="s-10 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                                        <tbody>
                                        <tr>
                                            <td style="line-height: 40px; font-size: 40px; width: 100%; height: 40px; margin: 0;" align="left" width="100%" height="40">
                                                &#160;
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table class="ax-center" role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                                        <tbody>
                                        <tr>
                                            <td style="line-height: 24px; font-size: 16px; margin: 0;" align="left">
                                                <x-barua-img :src="Barua::asset('img/empty-state.svg')" style="margin: 20px auto 0;" alt="image" width="120" height="120"/>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table class="s-10 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                                        <tbody>
                                        <tr>
                                            <td style="line-height: 40px; font-size: 40px; width: 100%; height: 40px; margin: 0;" align="left" width="100%" height="40">
                                                &#160;
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table class="card p-6 p-lg-10 space-y-4" role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-radius: 6px; border-collapse: separate !important; width: 100%; overflow: hidden; border: 1px solid #e1deda;" bgcolor="#fdfcfa">
                                        <tbody>
                                        <tr>
                                            <td style="line-height: 24px; font-size: 16px; width: 100%; margin: 0; padding: 40px;" align="left" bgcolor="#fdfcfa">
                                                <h1 class="h3 fw-700" style="padding-top: 0; padding-bottom: 0; font-weight: 700 !important; vertical-align: baseline; font-size: 28px; line-height: 33.6px; margin: 0;" align="left">
                                                    Dear {{$name ?? "[User's Name]"}},
                                                </h1>
                                                <table class="s-4 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                                                    <tbody>
                                                    <tr>
                                                        <td style="line-height: 16px; font-size: 16px; width: 100%; height: 16px; margin: 0;" align="left" width="100%" height="16">
                                                            &#160;
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <p style="line-height: 24px;font-size: 16px;width: 100%;margin: 5px auto 5px;" align="left">
                                                    Welcome to {{$companyName ?? "[Your Company Name]"}}! We're thrilled to have you on board. You've taken the first step towards
                                                    {{$solutionOrOffer ?? "[what your company offers]"}} and we're excited to help you get started.
                                                </p>
                                                <table class="s-4 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                                                    <tbody>
                                                    <tr>
                                                        <td style="line-height: 16px; font-size: 16px; width: 100%; height: 16px; margin: 0;" align="left" width="100%" height="16">
                                                            &#160;
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <p style="line-height: 24px;font-size: 16px;width: 100%;margin: 5px auto 5px;" align="left">
                                                    Here are a few things you can do right away:
                                                </p>
                                                <table class="s-4 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                                                    <tbody>
                                                    <tr>
                                                        <td style="line-height: 16px; font-size: 16px; width: 100%; height: 16px; margin: 0;" align="left" width="100%" height="16">
                                                            &#160;
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <ul style="line-height: 24px;font-size: 16px;width: 100%;margin: 5px auto 5px;" align="left">
                                                    <li>Complete your profile to personalize your experience.</li>
                                                    <li>Browse our {{$productOrService ?? "[product/service]"}} and discover what's in store for you.</li>
                                                    <li>Reach out to our support team anytime you have questions or need assistance.</li>
                                                </ul>
                                                <table class="s-4 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                                                    <tbody>
                                                    <tr>
                                                        <td style="line-height: 16px; font-size: 16px; width: 100%; height: 16px; margin: 0;" align="left" width="100%" height="16">
                                                            &#160;
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <p style="line-height: 24px;font-size: 16px;width: 100%;margin: 5px auto 5px;" align="left">
                                                    Thank you for choosing {{$productName ?? "[Your Company Name]"}}. We're here to support you every step of the way!
                                                </p>
                                                <table class="s-4 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                                                    <tbody>
                                                    <tr>
                                                        <td style="line-height: 16px; font-size: 16px; width: 100%; height: 16px; margin: 0;" align="left" width="100%" height="16">
                                                            &#160;
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <p style="line-height: 24px;font-size: 16px;width: 100%;margin: 5px auto 5px;" align="left">
                                                    Best regards,<br/>
                                                    The {{$companyName ?? "[Your Company Name]"}} Team
                                                </p>
                                                <table class="s-6 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                                                    <tbody>
                                                    <tr>
                                                        <td style="line-height: 16px; font-size: 16px; width: 100%; height: 16px; margin: 0;" align="left" width="100%" height="16">
                                                            &#160;
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table class="s-10 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                                        <tbody>
                                        <tr>
                                            <td style="line-height: 40px; font-size: 40px; width: 100%; height: 40px; margin: 0;" align="left" width="100%" height="40">
                                                &#160;
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="text-muted text-center" style="color: #718096;" align="center">
                                        <p style="margin: 4px auto 6px; line-height: 14px; font-size: 14px">Sent with <span style="color: red">❤</span> from Simtabi.</p>
                                        <p style="margin: 4px auto; line-height: 18px; font-size: 14px">15 Scenic Pointe Dr, Ste 400 Draper, <br> UT 84020 US</p>
                                    </div>
                                    <div class="text-muted text-center" style="color: #718096; margin: 10px auto;" align="center">
                                        <p style="margin: 20px auto; line-height: 14px; font-size: 14px">Don't like these emails? <a href="{{$unsubscribeLink ?? "https://simtabi.com"}}">Unsubscribe</a>.</p>
                                    </div>
                                    <div class="text-muted text-center mb-2" style="color: #718096; margin-bottom: 10px;" align="center">
                                        <p style="margin: 4px auto; line-height: 14px; font-size: 14px">Copyright &copy; {{date('Y')}} {{$companyName ?? '$companyName'}} &middot; All rights reserved.</p>
                                    </div>
                                    <table class="s-10 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                                        <tbody>
                                        <tr>
                                            <td style="line-height: 40px; font-size: 40px; width: 100%; height: 40px; margin: 0;" align="left" width="100%" height="40">
                                                &#160;
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <!--[if (gte mso 9)|(IE)]>
                        </td>
                        </tr>
                        </tbody>
                        </table>
                        <![endif]-->
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
