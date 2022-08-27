<?php

namespace App\Incentive\Domain\User\ValueObject;

use DateInterval;

class BoosterBonusPointsValidFor
{
    private DateInterval $interval;

    private function __construct(int $validForDays)
    {
        $this->interval = new DateInterval('P'.$validForDays.'D');
    }

    public static function fromDaysAsInt(int $validForDays): self
    {
        return new self($validForDays);
    }

    public function toDateInterval(): DateInterval
    {
        return  $this->interval;
    }

    public function toInt(): int
    {
        return  $this->interval->d;
    }
}
