<?php

namespace App\Incentive\Domain\User\ValueObject;

use App\Incentive\Domain\User\Exception\InvalidBonusPointsException;
use Assert\Assertion;

class ActionBonusPoints
{
    private int $actionBonusPoints;

    public static function fromInt(int $actionBonusPoints): self
    {
        return new self($actionBonusPoints);
    }

    private function __construct(int $actionBonusPoints)
    {
        try {
            Assertion::integer($actionBonusPoints);
        } catch (\Exception $e) {
            throw InvalidBonusPointsException::reason($e->getMessage());
        }

        $this->actionBonusPoints = $actionBonusPoints;
    }

    public function toInt(): int
    {
        return $this->actionBonusPoints;
    }

}
