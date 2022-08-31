<?php

namespace App\Incentive\Domain\User\ValueObject;

use App\Incentive\Domain\User\Exception\InvalidDateFormatException;
use Carbon\Carbon;

class CompletedAt
{
    const FORMAT = 'Y-m-d H:i:s';

    private Carbon $completedAt;

    public static function fromString(string $completedAt): self
    {
        if (!Carbon::hasFormat($completedAt, self::FORMAT)) {
            throw new InvalidDateFormatException($completedAt, self::FORMAT);
        }
        return new self(Carbon::create($completedAt));
    }

    private function __construct(Carbon $completedAt)
    {
        $this->completedAt = $completedAt;
    }

    public function toString(): string
    {
        return $this->completedAt->format(self::FORMAT);
    }

    public function toCarbon(): Carbon
    {
        return $this->completedAt;
    }
}
