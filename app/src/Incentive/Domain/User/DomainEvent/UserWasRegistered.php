<?php

namespace App\Incentive\Domain\User\DomainEvent;

use App\Incentive\Domain\User\ValueObject\EmailAddress;
use App\Incentive\Domain\User\ValueObject\UserId;
use App\Incentive\Domain\User\ValueObject\UserName;
use Broadway\Serializer\Serializable;


class UserWasRegistered implements Serializable
{
    private UserId $userId;
    private UserName $userName;
    private EmailAddress $emailAddress;

    public function __construct(UserId $userId, UserName $userName, EmailAddress $emailAddress)
    {
        $this->userId = $userId;
        $this->userName = $userName;
        $this->emailAddress = $emailAddress;
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

    public static function deserialize(array $data): self
    {
        return new self(
            UserId::fromString($data['userId']),
            UserName::fromString($data['userName']),
            EmailAddress::fromString($data['emailAddress'])
        );
    }

    public function serialize(): array
    {
        return [
            'userId' => $this->userId->toString(),
            'userName' => $this->userName->toString(),
            'emailAddress' => $this->emailAddress->toString()
        ];
    }
}
