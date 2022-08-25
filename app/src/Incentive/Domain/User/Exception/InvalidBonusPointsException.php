<?php

namespace App\Incentive\Domain\User\Exception;

use InvalidArgumentException;

class InvalidBonusPointsException extends InvalidArgumentException
{
    public static function reason(string $msg): self
    {
        return new self('Invalid user bonus points type ' . $msg);
    }
}
