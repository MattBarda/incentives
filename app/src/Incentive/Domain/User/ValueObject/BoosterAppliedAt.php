<?php

namespace App\Incentive\Domain\User\ValueObject;

use App\Incentive\Domain\User\Exception\InvalidDateFormatException;
use Carbon\Carbon;

class BoosterAppliedAt
{
    const FORMAT = 'Y-m-d H:i:s';

    private Carbon $appliedAt;

    public static function fromString(string $appliedAt): self
    {
        //TODO do better validation
        if (!Carbon::hasFormat($appliedAt, self::FORMAT)) {
            throw new InvalidDateFormatException($appliedAt, self::FORMAT);
        }
        return new self(Carbon::create($appliedAt));
    }

    private function __construct(Carbon $appliedAt)
    {
        $this->appliedAt = $appliedAt;
    }

    public function toString(): string
    {
        return $this->appliedAt->format(self::FORMAT);
    }

    public function toCarbon(): Carbon
    {
        return $this->appliedAt;
    }
}
