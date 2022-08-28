<?php

namespace App\Incentive\Domain\User\ValueObject;

use App\Incentive\Domain\User\Exception\InvalidDateFormatException;
use Carbon\Carbon;

class ExpirationDate
{
    const FORMAT = 'Y-m-d H:i:s';

    private Carbon $expirationDate;

    public static function fromBoosterBonusPoints(BoosterBonusPoints $boosterBonusPoints): self
    {
        $now = Carbon::now();
        return new self(
            $now->add($boosterBonusPoints->boosterBonusPointsValidFor()->toDateInterval())
        );
    }

    public static function fromString(string $expirationDate): self
    {
        //TODO do better validation
        if (!Carbon::hasFormat($expirationDate, self::FORMAT)) {
            throw new InvalidDateFormatException($expirationDate, self::FORMAT);
        }
        return new self(Carbon::create($expirationDate));
    }

    private function __construct(Carbon $expirationDate)
    {
        $this->expirationDate = $expirationDate;
    }

    public function toString(): string
    {
        return $this->expirationDate->format(self::FORMAT);
    }

    public function toCarbon(): Carbon
    {
        return $this->expirationDate;
    }
}