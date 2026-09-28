<?php

declare(strict_types=1);

// Registered as `laranail.barua`: read these as config('laranail.barua.<key>').
return [

    /*
    |--------------------------------------------------------------------------
    | Debug pages
    |--------------------------------------------------------------------------
    |
    | Preview pages for the bundled templates at /barua/debug. They send real
    | mail from GET requests, so they are registered only when this is on AND
    | the application environment is `local`.
    |
    */

    'dev_mode' => (bool) env('LARANAIL_BARUA_DEV_MODE', false),

    // The model the debug pages address a preview to (its first row).
    // A string, not ::class: the application's model does not exist inside this package.
    'user_class' => 'App\\Models\\User',

    /*
    |--------------------------------------------------------------------------
    | Behaviour
    |--------------------------------------------------------------------------
    |
    | `throw_errors` turns every builder error into a BaruaException instead of
    | a logged warning. `enable_send_mail` off builds messages without sending
    | them and dispatches SendMailDisabled instead.
    |
    */

    'throw_errors'     => (bool) env('LARANAIL_BARUA_THROW_ERRORS', false),
    'enable_send_mail' => (bool) env('LARANAIL_BARUA_SEND_MAIL', true),

    /*
    |--------------------------------------------------------------------------
    | Attachments
    |--------------------------------------------------------------------------
    |
    | The largest attachment accepted, in bytes or a size such as "12MB", and
    | the MIME types accepted. The env variable takes a comma-separated list.
    |
    */

    'max_file_size' => env('LARANAIL_BARUA_MAX_FILE_SIZE', '12MB'),

    'allowed_mime_types' => array_values(array_filter(array_map(
        trim(...),
        explode(',', (string) env('LARANAIL_BARUA_ALLOWED_MIME_TYPES', 'application/pdf,image/jpeg,image/png')),
    ))),

    /*
    |--------------------------------------------------------------------------
    | Default sender
    |--------------------------------------------------------------------------
    */

    'sender' => [
        'email' => env('MAIL_FROM_ADDRESS', 'noreply@example.com'),
        'name'  => env('MAIL_FROM_NAME', 'No Reply'),
    ],

    /*
    |--------------------------------------------------------------------------
    | CSS inlining
    |--------------------------------------------------------------------------
    |
    | Inline CSS into outgoing HTML mail, which most mail clients require.
    | Applies to every message the application sends while enabled.
    |
    | `stylesheets` are always inlined; use absolute paths, for example
    | public_path('css/mail.css'). A <link rel="stylesheet"> in a template is
    | inlined only when it points at a .css file inside the public directory.
    |
    */

    'inline_css' => (bool) env('LARANAIL_BARUA_INLINE_CSS', true),

    'stylesheets' => [
        // public_path('css/mail.css'),
    ],

];
