<?php

namespace App\Tests\Incentive\Domain\User\DomainEvent;

use App\Incentive\Domain\User\DomainEvent\UserWasRegistered;
use App\Incentive\Domain\User\ValueObject\EmailAddress;
use App\Incentive\Domain\User\ValueObject\UserId;
use App\Incentive\Domain\User\ValueObject\UserName;
use Broadway\Serializer\Testing\SerializableEventTestCase;

class UserWasRegisteredTest extends SerializableEventTestCase
{
    /**
     * @test
     */
    public function getter_of_event_work()
    {
        $userId1 = '00000000-0000-0000-0000-000000000001';
        $username1 = 'username';
        $userEmailAddress1 = 'user@domain.com';

        $event = new UserWasRegistered(
            UserId::fromString($userId1),
            UserName::fromString($username1),
            EmailAddress::fromString($userEmailAddress1)
        );

        $this->assertEquals($userId1, $event->userId()->toString());
        $this->assertEquals($username1, $event->userName()->toString());
        $this->assertEquals($userEmailAddress1, $event->emailAddress()->toString());
    }

    protected function createEvent()
    {
        $userId1 = '00000000-0000-0000-0000-000000000001';
        $username1 = 'username';
        $userEmailAddress1 = 'user@domain.com';

        return new UserWasRegistered(
            UserId::fromString($userId1),
            UserName::fromString($username1),
            EmailAddress::fromString($userEmailAddress1)
        );
    }
}
