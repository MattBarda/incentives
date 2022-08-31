<?php

namespace App\Tests\Incentive\Domain\User\Command;

use App\Incentive\Domain\User\Command\RentComplete;
use App\Incentive\Domain\User\DomainEvent\RentCompleted;
use App\Incentive\Domain\User\DomainEvent\RentStarted;
use App\Incentive\Domain\User\DomainEvent\UserWasRegistered;
use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\CompletedAt;
use App\Incentive\Domain\User\ValueObject\EmailAddress;
use App\Incentive\Domain\User\ValueObject\RentDuration;
use App\Incentive\Domain\User\ValueObject\StartedAt;
use App\Incentive\Domain\User\ValueObject\UserId;
use App\Incentive\Domain\User\ValueObject\UserName;
use App\Tests\Incentive\Domain\User\CommandHandler\RentCompleteHandlerTest;
use Carbon\Carbon;
use RuntimeException;

class RentCompleteTest extends RentCompleteHandlerTest
{
    /**
     * @test
     */
    public function it_completes_the_rent()
    {
        $userId = '00000000-0000-0000-0000-000000000000';
        $username = 'username';
        $userEmailAddress = 'user@domain.com';

        $rentId = '00000000-0000-0000-0000-000000000001';
        $rentStartedAt = Carbon::create('2022-08-27 20:00:00');
        $rentCompletedAt = Carbon::create('2022-08-27 20:00:01');
        $expectedRentBonusPointsPerDay = 2;
        $expectedRentBonusPoints = 0;

        $this->scenario
            ->withAggregateId($userId)
            ->given([
                new UserWasRegistered(
                    UserId::fromString($userId),
                    UserName::fromString($username),
                    EmailAddress::fromString($userEmailAddress)
                ),
                new RentStarted(
                    UserId::fromString($userId),
                    ActionId::fromString($rentId),
                    StartedAt::fromString($rentStartedAt),
                ),
            ])
            ->when(new RentComplete($userId, $rentId, $rentCompletedAt))
            ->then([
                new RentCompleted(
                    UserId::fromString($userId),
                    ActionId::fromString($rentId),
                    ActionBonusPoints::fromInt($expectedRentBonusPointsPerDay),
                    RentDuration::fromStartedAtAndCompletedAt(
                        StartedAt::fromString($rentStartedAt),
                        CompletedAt::fromString($rentCompletedAt)
                    ),
                    ActionBonusPoints::fromInt($expectedRentBonusPoints)
                ),
            ]);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_trying_to_complete_a_rent_that_was_not_started()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Trying to complete rent with id: 00000000-0000-0000-0000-000000000001 that was not started'
        );

        $userId = '00000000-0000-0000-0000-000000000000';
        $username = 'username';
        $userEmailAddress = 'user@domain.com';

        $rentId = '00000000-0000-0000-0000-000000000001';
        $rentStartedAt = Carbon::create('2022-08-27 20:00:00');
        $rentCompletedAt = Carbon::create('2022-08-27 20:00:01');
        $expectedRentBonusPointsPerDay = 2;
        $expectedRentBonusPoints = 2;

        $this->scenario
            ->withAggregateId($userId)
            ->given([
                new UserWasRegistered(
                    UserId::fromString($userId),
                    UserName::fromString($username),
                    EmailAddress::fromString($userEmailAddress)
                )
            ])
            ->when(new RentComplete($userId, $rentId, $rentCompletedAt))
            ->then([
                new RentCompleted(
                    UserId::fromString($userId),
                    ActionId::fromString($rentId),
                    ActionBonusPoints::fromInt($expectedRentBonusPointsPerDay),
                    RentDuration::fromStartedAtAndCompletedAt(
                        StartedAt::fromString($rentStartedAt),
                        CompletedAt::fromString($rentCompletedAt)
                    ),
                    ActionBonusPoints::fromInt($expectedRentBonusPoints)
                ),
            ]);
    }

    /**
     * @test
     */
    public function it_adds_points_for_rent_that_duration_longer_then_one_day()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Trying to complete rent with id: 00000000-0000-0000-0000-000000000001 that was not started'
        );

        $userId = '00000000-0000-0000-0000-000000000000';
        $username = 'username';
        $userEmailAddress = 'user@domain.com';

        $rentId = '00000000-0000-0000-0000-000000000001';
        $rentStartedAt = Carbon::create('2022-08-27 20:00:00');
        $rentCompletedAt = Carbon::create('2022-08-28 20:00:01');
        $rentBonusPointsPerDay = 2;
        $rentBonusPoints = 2;

        $this->scenario
            ->withAggregateId($userId)
            ->given([
                new UserWasRegistered(
                    UserId::fromString($userId),
                    UserName::fromString($username),
                    EmailAddress::fromString($userEmailAddress)
                )
            ])
            ->when(new RentComplete($userId, $rentId, $rentCompletedAt))
            ->then([
                new RentCompleted(
                    UserId::fromString($userId),
                    ActionId::fromString($rentId),
                    ActionBonusPoints::fromInt($rentBonusPointsPerDay),
                    RentDuration::fromStartedAtAndCompletedAt(
                        StartedAt::fromString($rentStartedAt),
                        CompletedAt::fromString($rentCompletedAt)
                    ),
                    ActionBonusPoints::fromInt($rentBonusPoints)
                ),
            ]);
    }

    /**
     * @test
     */
    public function it_multiplies_points_by_each_day_of_rent()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Trying to complete rent with id: 00000000-0000-0000-0000-000000000001 that was not started'
        );

        $userId = '00000000-0000-0000-0000-000000000000';
        $username = 'username';
        $userEmailAddress = 'user@domain.com';

        $rentId = '00000000-0000-0000-0000-000000000001';
        $rentStartedAt = Carbon::create('2022-08-10 20:00:00');
        $rentCompletedAt = Carbon::create('2022-08-15 20:00:01');
        $rentBonusPointsPerDay = 2;
        $rentBonusPoints = 10;

        $this->scenario
            ->withAggregateId($userId)
            ->given([
                new UserWasRegistered(
                    UserId::fromString($userId),
                    UserName::fromString($username),
                    EmailAddress::fromString($userEmailAddress)
                )
            ])
            ->when(new RentComplete($userId, $rentId, $rentCompletedAt))
            ->then([
                new RentCompleted(
                    UserId::fromString($userId),
                    ActionId::fromString($rentId),
                    ActionBonusPoints::fromInt($rentBonusPointsPerDay),
                    RentDuration::fromStartedAtAndCompletedAt(
                        StartedAt::fromString($rentStartedAt),
                        CompletedAt::fromString($rentCompletedAt)
                    ),
                    ActionBonusPoints::fromInt($rentBonusPoints)
                ),
            ]);
    }
}
