<?php

namespace App\Incentive\Domain\User\ValueObject;

class BoosterApplicableForAction
{
    private string $action;

    private function __construct(string $action)
    {
        $this->action = $action;
    }

    public static function fromString(string $action): self
    {
        return new self($action);
    }

    public function toString(): string
    {
        return $this->action;
    }
}
