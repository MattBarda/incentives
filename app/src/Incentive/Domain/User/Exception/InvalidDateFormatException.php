<?php

namespace App\Incentive\Domain\User\Exception;

use InvalidArgumentException;

class InvalidDateFormatException extends InvalidArgumentException
{
    public static function reason(string $stringDate, string $expectedFormat): self
    {
        return new self(sprintf(
            'Invalid date format for date string: %s , expected format: $s',
            $stringDate,
            $expectedFormat
        ));
    }
}
