<?php

declare(strict_types=1);

use Simtabi\Laranail\Barua\Tests\TestCase;
use Simtabi\Laranail\Barua\Tests\DevModeTestCase;

uses(DevModeTestCase::class)->in('DevMode');
uses(TestCase::class)->in('Feature', 'Unit');

/*
 * Compile Blade from scratch once per run, so a template compiled against an earlier
 * version of a view cannot keep passing locally while CI (empty cache) fails.
 */
$compiled = __DIR__ . '/../vendor/orchestra/testbench-core/laravel/storage/framework/views';

if (is_dir($compiled)) {
    foreach (glob($compiled . '/*.php') ?: [] as $template) {
        @unlink($template);
    }
}
