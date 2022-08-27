<?php

namespace App\Incentive\Domain\User\ValueObject;

use App\Incentive\Domain\User\Exception\InvalidBonusPointsException;
use Assert\Assertion;

class BoosterBonusPoints
{
    private int $boosterBonusPoints;
    private BoosterBonusPointsValidFor $boosterBonusPointsValidFor;

    public static function fromIntAndExpireAt(
        int $boosterBonusPoints,
        BoosterBonusPointsValidFor $boosterBonusPointsValidFor
    ): self {
        return new self($boosterBonusPoints, $boosterBonusPointsValidFor);
    }

    private function __construct(
        int $actionBonusPoints,
        BoosterBonusPointsValidFor $boosterBonusPointsValidFor
    ) {
        try {
            Assertion::integer($actionBonusPoints);
        } catch (\Exception $e) {
            throw InvalidBonusPointsException::reason($e->getMessage());
        }
        $this->boosterBonusPoints = $actionBonusPoints;
        $this->boosterBonusPointsValidFor = $boosterBonusPointsValidFor;
    }

    public function pointsAsInt(): int
    {
        return $this->boosterBonusPoints;
    }

    public function boosterBonusPointsValidFor(): BoosterBonusPointsValidFor
    {
        return $this->boosterBonusPointsValidFor;
    }
}
