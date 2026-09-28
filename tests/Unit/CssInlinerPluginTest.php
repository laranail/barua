<?php

declare(strict_types=1);

use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\TextPart;
use Illuminate\Mail\Events\MessageSending;
use Simtabi\Laranail\Barua\Plugins\CssInlinerPlugin;

function baruaPublicDir(): string
{
    $dir = sys_get_temp_dir() . '/barua-public-' . bin2hex(random_bytes(4));
    mkdir($dir . '/css', 0o777, true);
    file_put_contents($dir . '/css/mail.css', 'p { color: rgb(1, 2, 3); }');

    return $dir;
}

function baruaInline(string $html, string $public, array $always = []): string
{
    $email = (new Email)->from('a@example.com')->to('b@example.com')->html($html);

    new CssInlinerPlugin($always, $public)->handle(new MessageSending($email));

    $body = $email->getBody();

    return $body instanceof TextPart ? $body->getBody() : $body->bodyToString();
}

it('inlines a stylesheet linked from inside the public directory', function (): void {
    $html = baruaInline('<html><head><link rel="stylesheet" href="/css/mail.css"></head><body><p>Hi</p></body></html>', baruaPublicDir());

    expect($html)->toContain('color: rgb(1, 2, 3)')->not->toContain('<link');
});

it('does not read a file outside the public directory (B11)', function (): void {
    $secret = tempnam(sys_get_temp_dir(), 'secret') . '.css';
    file_put_contents($secret, 'p { content: "top-secret"; }');

    $html = baruaInline("<html><head><link rel=\"stylesheet\" href=\"{$secret}\"></head><body><p>Hi</p></body></html>", baruaPublicDir());

    expect($html)->not->toContain('top-secret')->not->toContain('<link');
});

it('does not follow a traversal out of the public directory (B11)', function (): void {
    $public = baruaPublicDir();
    file_put_contents(dirname($public) . '/outside.css', 'p { content: "escaped"; }');

    $html = baruaInline('<html><head><link rel="stylesheet" href="/../outside.css"></head><body><p>Hi</p></body></html>', $public);

    expect($html)->not->toContain('escaped');
});

it('does not fetch a remote stylesheet (B11)', function (): void {
    $html = baruaInline('<html><head><link rel="stylesheet" href="http://127.0.0.1:1/x.css"></head><body><p>Hi</p></body></html>', baruaPublicDir());

    expect($html)->toContain('Hi')->not->toContain('<link');
});

it('always inlines the configured stylesheets', function (): void {
    $public = baruaPublicDir();

    $html = baruaInline('<html><body><p>Hi</p></body></html>', $public, [$public . '/css/mail.css']);

    expect($html)->toContain('color: rgb(1, 2, 3)');
});
