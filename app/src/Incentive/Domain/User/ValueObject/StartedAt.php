<?php

namespace App\Incentive\Domain\User\ValueObject;

use App\Incentive\Domain\User\Exception\InvalidDateFormatException;
use Carbon\Carbon;

class StartedAt
{
    const FORMAT = 'Y-m-d H:i:s';

    private Carbon $finishedAt;

    public static function now(): self
    {
        return new self(Carbon::create('now'));
    }

    public static function fromString(string $finishedAt): self
    {
        //TODO do better validation
        if (!Carbon::hasFormat($finishedAt, self::FORMAT)) {
            throw new InvalidDateFormatException($finishedAt, self::FORMAT);
        }
        return new self(Carbon::create($finishedAt));
    }

    private function __construct(Carbon $finishedAt)
    {
        $this->finishedAt = $finishedAt;
    }

    public function toString(): string
    {
        return $this->finishedAt->format(self::FORMAT);
    }

    public function toCarbon(): Carbon
    {
        return $this->finishedAt;
    }
}
