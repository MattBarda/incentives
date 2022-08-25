<?php

namespace App\Incentive\Domain\User\ValueObject;

use Assert\Assertion;
use App\Incentive\Domain\User\Exception\InvalidNameException;

final class UserName
{
    private string $name;

    public static function fromString(string $name): self
    {
        return new self($name);
    }

    private function __construct(string $name)
    {
        try {
            Assertion::notEmpty($name);
        } catch (\Exception $e) {
            throw InvalidNameException::reason($e->getMessage());
        }

        $this->name = $name;
    }

    public function toString(): string
    {
        return $this->name;
    }

    public function sameValueAs(self $object): bool
    {
        return \get_class($this) === \get_class($object) && $this->name === $object->name;
    }
}
