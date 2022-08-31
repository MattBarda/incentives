<?php

namespace App\Incentive\Domain\User\ValueObject;

use App\Incentive\Domain\User\Exception\InvalidDateFormatException;
use Carbon\Carbon;

class BoosterActiveFrom
{
    const FORMAT = 'Y-m-d H:i:s';

    private Carbon $validFrom;

    public static function fromString(string $validFrom): self
    {
        if (!Carbon::hasFormat($validFrom, self::FORMAT)) {
            throw new InvalidDateFormatException($validFrom, self::FORMAT);
        }
        return new self(Carbon::create($validFrom));
    }

    private function __construct(Carbon $validFrom)
    {
        $this->validFrom = $validFrom;
    }

    public function toString(): string
    {
        return $this->validFrom->format(self::FORMAT);
    }

    public function toCarbon(): Carbon
    {
        return $this->validFrom;
    }
}
