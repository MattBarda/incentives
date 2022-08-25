<?php

namespace App\Incentive\Domain\User\ValueObject;

use Assert\Assertion as Assert;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class DeliveryId
{
    private UuidInterface $uuid;

    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $todoId): self
    {
        Assert::uuid($todoId);
        return new self(Uuid::fromString($todoId));
    }

    private function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }
}
