<?php

namespace App\Incentive\Domain\User\ValueObject;

final class EmailAddress
{
    /**
     * @var string
     */
    private $email;

    public static function fromString(string $email): EmailAddress
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email address');
        }

        return new self($email);
    }

    private function __construct(string $email)
    {
        $this->email = $email;
    }

    public function toString(): string
    {
        return $this->email;
    }

    public function sameValueAs(self $other): bool
    {
        return \get_class($this) === \get_class($other) && $this->toString() === $other->toString();
    }
}
