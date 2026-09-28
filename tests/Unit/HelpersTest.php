<?php

declare(strict_types=1);

use Simtabi\Laranail\Barua\Support\Helpers;
use Simtabi\Laranail\Barua\Support\TextFormatter;

it('converts human-readable sizes to bytes', function (string $in, float|false $out): void {
    expect(Helpers::humanReadableToBytes($in))->toBe($out);
})->with([
    ['2048', 2048.0],
    ['12MB', 12.0 * 1024 ** 2],
    ['1.5 kb', 1536.0],
    ['12 potatoes', false],
    ['', false],
]);

it('reads the configured limit in bytes', function (): void {
    config()->set('laranail.barua.max_file_size', '2KB');

    expect(Helpers::getDefaultMaximumFileSize())->toBe(2048);
});

it('reads the MIME list from an array or a comma-separated string', function (): void {
    config()->set('laranail.barua.allowed_mime_types', 'application/PDF, image/png,');
    expect(Helpers::getAllowedMimeTypes())->toBe(['application/pdf', 'image/png']);

    config()->set('laranail.barua.allowed_mime_types', ['image/jpeg']);
    expect(Helpers::getAllowedMimeTypes())->toBe(['image/jpeg']);
});

it('replaces :placeholders', function (): void {
    expect(TextFormatter::replacePlaceholders('Hi :name at :place', ['name' => 'Ada', 'place' => 'Acme']))
        ->toBe('Hi Ada at Acme');
});
