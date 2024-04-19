<?php declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Configuration
    |--------------------------------------------------------------------------
    |
    | Control the default configuration of the package
    |
    */

    'dev_mode'           => env('BARUA_ENABLE_DEV_MODE', false),
    'user_class'         => \App\Models\User::class,
    'throw_errors'       => env('BARUA_ENABLE_ERROR_THROWING', false),
    'max_file_size'      => env('BARUA_MAX_FILE_SIZE', '12mb'),
    'allowed_mime_types' => env('BARUA_ALLOWED_MIME_TYPES', 'application/pdf,image/jpeg,image/png,'),
    'enable_send_mail'   => env('BARUA_ENABLE_SEND_MAIL', true),

    /*
    |--------------------------------------------------------------------------
    | Mailer settings
    |--------------------------------------------------------------------------
    |
    | The mailer to use for sending emails
    |
    */

    'sender' => [
        'email' => env('MAIL_FROM_ADDRESS', 'noreply@domain.com'),
        'name'  => env('MAIL_FROM_NAME', 'No Reply'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Stylesheet Files
    |--------------------------------------------------------------------------
    |
    | Css file of your style for your emails
    | The content of these files will be added directly into the file
    | Use absolute paths, i.e. public_path('css/main.css')
    |
    */

    'stylesheets' => [
        // public_path('css/main.css'),
    ],

];
