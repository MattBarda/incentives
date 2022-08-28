<?php

namespace App\Incentive\Domain\User\ValueObject;

use App\Incentive\Domain\User\Exception\InvalidDateFormatException;
use Carbon\Carbon;

class BoosterActiveTo
{
    const FORMAT = 'Y-m-d H:i:s';

    private Carbon $activeTo;

    public static function fromString(string $activeTo): self
    {
        //TODO do better validation
        if (!Carbon::hasFormat($activeTo, self::FORMAT)) {
            throw new InvalidDateFormatException($activeTo, self::FORMAT);
        }
        return new self(Carbon::create($activeTo));
    }

    private function __construct(Carbon $activeTo)
    {
        $this->activeTo = $activeTo;
    }

    public function toString(): string
    {
        return $this->activeTo->format(self::FORMAT);
    }

    public function toCarbon(): Carbon
    {
        return $this->activeTo;
    }
}
