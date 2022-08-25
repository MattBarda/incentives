<?php

namespace App\Incentive\Domain\User;

use App\Incentive\Domain\User\DomainEvent\UserWasRegistered;
use App\Incentive\Domain\User\ValueObject\EmailAddress;
use App\Incentive\Domain\User\ValueObject\UserId;
use App\Incentive\Domain\User\ValueObject\UserName;
use Broadway\EventSourcing\EventSourcedAggregateRoot;

class User extends EventSourcedAggregateRoot
{
    private UserId $userId;
    private UserName $userName;
    private EmailAddress $emailAddress;

    public function getAggregateRootId(): string
    {
        return $this->userId->toString();
    }

    public static function registerWithData(
        UserId $userId,
        UserName $name,
        EmailAddress $emailAddress
    ): self {
        $user = new self();
        $user->register($userId, $name, $emailAddress);

        return $user;
    }

    private function register(
        UserId $userId,
        UserName $name,
        EmailAddress $emailAddress
    ) {
        $this->apply(
            new UserWasRegistered($userId, $name, $emailAddress)
        );
    }

    protected function whenUserWasRegistered(UserWasRegistered $event): void
    {
        $this->userId = $event->userId();
        $this->userName = $event->userName();
        $this->emailAddress = $event->emailAddress();
    }

    protected function applyUserWasRegistered(UserWasRegistered $event): void
    {
        $this->userId = $event->userId();
        $this->userName = $event->userName();
        $this->emailAddress = $event->emailAddress();
    }

}
