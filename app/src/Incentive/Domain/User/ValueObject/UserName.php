<?php

namespace App\Incentive\Domain\User\ValueObject;

use Assert\Assertion;
use App\Incentive\Domain\User\Exception\InvalidNameException;

final class UserName
{
    private string $userName;

    public static function fromString(string $userName): self
    {
        return new self($userName);
    }

    private function __construct(string $userName)
    {
        try {
            Assertion::notEmpty($userName);
        } catch (\Exception $e) {
            throw InvalidNameException::reason($e->getMessage());
        }

        $this->userName = $userName;
    }

    public function toString(): string
    {
        return $this->userName;
    }

    public function sameValueAs(self $object): bool
    {
        return \get_class($this) === \get_class($object) && $this->userName === $object->userName;
    }
}
