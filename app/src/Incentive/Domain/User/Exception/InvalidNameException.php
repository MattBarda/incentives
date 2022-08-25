<?php

namespace App\Incentive\Domain\User\Exception;

use InvalidArgumentException;

final class InvalidNameException extends InvalidArgumentException
{
    public static function reason(string $msg): self
    {
        return new self('Invalid user name because ' . $msg);
    }
}
