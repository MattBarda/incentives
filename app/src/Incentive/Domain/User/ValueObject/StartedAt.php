<?php

namespace App\Incentive\Domain\User\ValueObject;

use App\Incentive\Domain\User\Exception\InvalidDateFormatException;
use Carbon\Carbon;

class StartedAt
{
    const FORMAT = 'Y-m-d H:i:s';

    private Carbon $startedAt;

    public static function fromString(string $startedAt): self
    {
        if (!Carbon::hasFormat($startedAt, self::FORMAT)) {
            throw new InvalidDateFormatException($startedAt, self::FORMAT);
        }
        return new self(Carbon::create($startedAt));
    }

    private function __construct(Carbon $startedAt)
    {
        $this->startedAt = $startedAt;
    }

    public function toString(): string
    {
        return $this->startedAt->format(self::FORMAT);
    }

    public function toCarbon(): Carbon
    {
        return $this->startedAt;
    }
}
