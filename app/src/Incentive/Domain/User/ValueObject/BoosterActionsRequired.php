<?php

namespace App\Incentive\Domain\User\ValueObject;

class BoosterActionsRequired
{
    private int $boosterActionsRequired;

    public static function fromInt(
        int $boosterActionsRequired,
    ): self {
        return new self($boosterActionsRequired);
    }

    private function __construct(
        int $boosterActionsRequired,
    ) {
        $this->boosterActionsRequired = $boosterActionsRequired;
    }

    public function toInt(): int
    {
        return $this->boosterActionsRequired;
    }
}
