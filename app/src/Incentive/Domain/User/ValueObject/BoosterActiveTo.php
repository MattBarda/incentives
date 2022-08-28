<?php

namespace App\Incentive\Domain\User\ValueObject;

use App\Incentive\Domain\User\Exception\InvalidDateFormatException;
use Carbon\Carbon;

class BoosterActiveTo
{
    const FORMAT = 'Y-m-d H:i:s';

    private Carbon $validTo;

    public static function now(): self
    {
        return new self(Carbon::create('now'));
    }

    public static function fromString(string $validTo): self
    {
        //TODO do better validation
        if (!Carbon::hasFormat($validTo, self::FORMAT)) {
            throw new InvalidDateFormatException($validTo, self::FORMAT);
        }
        return new self(Carbon::create($validTo));
    }

    private function __construct(Carbon $validTo)
    {
        $this->validTo = $validTo;
    }

    public function toString(): string
    {
        return $this->validTo->format(self::FORMAT);
    }

    public function toCarbon(): Carbon
    {
        return $this->validTo;
    }
}
