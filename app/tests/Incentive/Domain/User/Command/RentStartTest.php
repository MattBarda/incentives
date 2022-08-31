<?php

namespace App\Tests\Incentive\Domain\User\Command;

use App\Incentive\Domain\User\Command\RentStart;
use App\Incentive\Domain\User\DomainEvent\RentStarted;
use App\Incentive\Domain\User\DomainEvent\UserWasRegistered;
use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\EmailAddress;
use App\Incentive\Domain\User\ValueObject\StartedAt;
use App\Incentive\Domain\User\ValueObject\UserId;
use App\Incentive\Domain\User\ValueObject\UserName;
use App\Tests\Incentive\Domain\User\CommandHandler\RentStartHandlerTest;
use Carbon\Carbon;

class RentStartTest extends RentStartHandlerTest
{
    /**
     * @test
     */
    public function it_starts_a_rent()
    {
        $userId = '00000000-0000-0000-0000-000000000000';
        $username = 'username';
        $userEmailAddress = 'user@domain.com';

        $rentId = '00000000-0000-0000-0000-000000000001';
        $rentStartedAt = Carbon::create('2022-08-27 20:00:00');

        $this->scenario
            ->withAggregateId($userId)
            ->given([
                new UserWasRegistered(
                    UserId::fromString($userId),
                    UserName::fromString($username),
                    EmailAddress::fromString($userEmailAddress)
                ),
            ])
            ->when(new RentStart($userId, $rentId, $rentStartedAt))
            ->then([
                new RentStarted(
                    UserId::fromString($userId),
                    ActionId::fromString($rentId),
                    StartedAt::fromString($rentStartedAt),
                ),
            ]);
    }
}
