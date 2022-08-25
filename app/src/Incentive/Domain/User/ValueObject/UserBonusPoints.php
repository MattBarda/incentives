<?php

namespace App\Incentive\Domain\User\ValueObject;

use App\Incentive\Domain\User\Exception\InvalidBonusPointsException;
use Assert\Assertion;

class UserBonusPoints
{
    private int $userBonusPoints;

    public static function fromInt(int $userBonusPoints): self
    {
        return new self($userBonusPoints);
    }

    private function __construct(int $userBonusPoints)
    {
        try {
            Assertion::integer($userBonusPoints);
        } catch (\Exception $e) {
            throw InvalidBonusPointsException::reason($e->getMessage());
        }

        $this->userBonusPoints = $userBonusPoints;
    }

    public static function init(int $userBonusPoints = 0): self
    {
        return new self($userBonusPoints);
    }

    public function toInt(): int
    {
        return $this->userBonusPoints;
    }

    public static function addActionBonusPoints(self $userBonusPoints, ActionBonusPoints $actionBonusPoints): self
    {
        return new self(
            ($userBonusPoints->toInt() + $actionBonusPoints->toInt())
        );
    }
}
