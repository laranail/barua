<?php declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Enums;

enum ViewType: string
{

    case MARKDOWN = 'markdown';
    case HTML     = 'html';
    case TEXT     = 'text';

    public function getType(): string
    {
        return match($this)
        {
            self::MARKDOWN => 'markdown',
            self::HTML     => 'html',
            self::TEXT     => 'text',
        };
    }

}
