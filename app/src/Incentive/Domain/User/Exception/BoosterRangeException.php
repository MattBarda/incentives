<?php

namespace App\Incentive\Domain\User\Exception;

use InvalidArgumentException;

class BoosterRangeException extends InvalidArgumentException
{
    public static function reason(string $msg): self
    {
        return new self('There was a problem with booster Range: ' . $msg);
    }
}
