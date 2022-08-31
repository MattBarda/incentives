<?php

namespace App\Tests\Incentive\Domain\User\Command;

use App\Incentive\Domain\User\Command\RideShareComplete;
use App\Incentive\Domain\User\DomainEvent\RideShareCompleted;
use App\Incentive\Domain\User\DomainEvent\UserWasRegistered;
use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\CompletedAt;
use App\Incentive\Domain\User\ValueObject\EmailAddress;
use App\Incentive\Domain\User\ValueObject\UserId;
use App\Incentive\Domain\User\ValueObject\UserName;
use App\Tests\Incentive\Domain\User\CommandHandler\RideShareCompleteHandlerTest;
use Carbon\Carbon;

class RideShareCompleteTest extends RideShareCompleteHandlerTest
{
    /**
     * @test
     */
    public function it_completes_a_ride_share()
    {
        $userId = '00000000-0000-0000-0000-000000000000';
        $username = 'username';
        $userEmailAddress = 'user@domain.com';

        $rideShareId = '00000000-0000-0000-0000-000000000001';
        $rideShareCompletedAt = Carbon::create('2022-08-27 20:00:00');
        $rideShareBonusPoints = 2;

        $this->scenario
            ->withAggregateId($userId)
            ->given([
                new UserWasRegistered(
                    UserId::fromString($userId),
                    UserName::fromString($username),
                    EmailAddress::fromString($userEmailAddress)
                ),
            ])
            ->when(new RideShareComplete($userId, $rideShareId, $rideShareCompletedAt))
            ->then([
                new RideShareCompleted(
                    UserId::fromString($userId),
                    ActionId::fromString($rideShareId),
                    CompletedAt::fromString($rideShareCompletedAt),
                    ActionBonusPoints::fromInt($rideShareBonusPoints)
                ),
            ]);
    }
}
