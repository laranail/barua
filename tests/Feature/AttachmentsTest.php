<?php

declare(strict_types=1);

use Simtabi\Laranail\Barua\Builders\MailBuilder;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;

function baruaPdf(int $bytes = 64): string
{
    $path = tempnam(sys_get_temp_dir(), 'barua') . '.pdf';
    file_put_contents($path, "%PDF-1.4\n" . str_repeat('A', $bytes));

    return $path;
}

it('has no view before one is set (B9)', function (): void {
    expect(new MailBuilder(new ErrorBuilder)->getView())->toBeNull();
});

it('keeps every attachment, not just the last (B5)', function (): void {
    $builder = new MailBuilder(new ErrorBuilder);

    $builder->setAttachments(baruaPdf(), 'one.pdf')->setAttachments(baruaPdf(), 'two.pdf');

    expect($builder->getAttachments())->toHaveCount(2)
        ->and(array_column($builder->getAttachments(), 'name'))->toBe(['one.pdf', 'two.pdf']);
});

it('reads the configured size limit and MIME list (B6)', function (): void {
    // Both helpers returned the config string through an array/bool return type.
    config()->set('laranail.barua.max_file_size', '1KB');

    $errors = new ErrorBuilder;
    $builder = new MailBuilder($errors);
    $builder->setAttachments(baruaPdf(4096));

    expect($builder->getAttachments())->toBeEmpty()
        ->and($errors->getErrors('attachments'))->toHaveCount(1);
});

it('skips a MIME type that is not allowed', function (): void {
    config()->set('laranail.barua.allowed_mime_types', ['image/png']);

    $errors = new ErrorBuilder;
    $builder = new MailBuilder($errors);
    $builder->setAttachments(baruaPdf());

    expect($builder->getAttachments())->toBeEmpty()
        ->and($errors->getErrors('attachments')[0])->toContain('invalid MIME type');
});

it('skips a file that does not exist', function (): void {
    $errors = new ErrorBuilder;
    $builder = new MailBuilder($errors);
    $builder->setAttachments('/nonexistent/file.pdf');

    expect($builder->getAttachments())->toBeEmpty()
        ->and($errors->getErrors('attachments'))->toHaveCount(1);
});
