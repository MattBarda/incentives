<?php

namespace App\Incentive\Domain\User\Command;

use App\Incentive\Domain\User\ValueObject\EmailAddress;
use App\Incentive\Domain\User\ValueObject\UserId;
use App\Incentive\Domain\User\ValueObject\UserName;

final class RegisterUser
{
    private UserId $userId;
    private UserName $userName;
    private EmailAddress $emailAddress;

    public function __construct(string $userId, string $userName, string $emailAddress)
    {
        //TODO validation
        $this->userId = UserId::fromString($userId);
        $this->userName = UserName::fromString($userName);
        $this->emailAddress = EmailAddress::fromString($emailAddress);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['userId'],
            $data['userName'],
            $data['emailAddress']
        );
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function userName(): UserName
    {
        return $this->userName;
    }

    public function emailAddress(): EmailAddress
    {
        return $this->emailAddress;
    }
}
