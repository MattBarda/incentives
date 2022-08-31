<?php

namespace App\Tests\Incentive\Domain\User\Command;

use App\Incentive\Domain\User\Command\RegisterUser;
use App\Incentive\Domain\User\DomainEvent\UserWasRegistered;
use App\Incentive\Domain\User\ValueObject\EmailAddress;
use App\Incentive\Domain\User\ValueObject\UserId;
use App\Incentive\Domain\User\ValueObject\UserName;
use App\Tests\Incentive\Domain\User\CommandHandler\RegisterUserHandlerTest;

class RegisterUserTest extends RegisterUserHandlerTest
{
    /**
     * @test
     */
    public function it_registers_a_user()
    {
        $userId = '00000000-0000-0000-0000-000000000000';
        $username = 'username';
        $userEmailAddress = 'user@domain.com';
        $this->scenario
            ->given([])
            ->when(new RegisterUser($userId, $username, $userEmailAddress))
            ->then([
                new UserWasRegistered(
                    UserId::fromString($userId),
                    UserName::fromString($username),
                    EmailAddress::fromString($userEmailAddress)
                ),
            ]);
    }

    /**
     * @test
     */
    public function it_registers_a_user_when_another_user_was_registered()
    {
        $userId1 = '00000000-0000-0000-0000-000000000001';
        $username1 = 'username';
        $userEmailAddress1 = 'user@domain.com';

        $userId2 = '00000000-0000-0000-0000-000000000002';
        $username2 = 'username2';
        $userEmailAddress2 = 'user@domain.com2';

        $this->scenario
            ->given([ new UserWasRegistered(
                UserId::fromString($userId1),
                UserName::fromString($username1),
                EmailAddress::fromString($userEmailAddress1)),
            ])
            ->when(new RegisterUser($userId2, $username2, $userEmailAddress2))
            ->then([
                new UserWasRegistered(
                    UserId::fromString($userId2),
                    UserName::fromString($username2),
                    EmailAddress::fromString($userEmailAddress2)
                ),
            ]);
    }
}
