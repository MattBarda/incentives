<?php

namespace App\Tests\Incentive\Domain\User\Command;

use App\Incentive\Domain\User\Command\RideShareComplete;
use App\Incentive\Domain\User\DomainEvent\BoosterActivated;
use App\Incentive\Domain\User\DomainEvent\BoosterApplied;
use App\Incentive\Domain\User\DomainEvent\RideShareCompleted;
use App\Incentive\Domain\User\DomainEvent\UserWasRegistered;
use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\BoosterActionsRequired;
use App\Incentive\Domain\User\ValueObject\BoosterActiveFrom;
use App\Incentive\Domain\User\ValueObject\BoosterActiveRange;
use App\Incentive\Domain\User\ValueObject\BoosterActiveTo;
use App\Incentive\Domain\User\ValueObject\BoosterApplicableForAction;
use App\Incentive\Domain\User\ValueObject\BoosterAppliedAt;
use App\Incentive\Domain\User\ValueObject\BoosterBonusPoints;
use App\Incentive\Domain\User\ValueObject\BoosterBonusPointsValidFor;
use App\Incentive\Domain\User\ValueObject\BoosterId;
use App\Incentive\Domain\User\ValueObject\CompletedAt;
use App\Incentive\Domain\User\ValueObject\EmailAddress;
use App\Incentive\Domain\User\ValueObject\ExpirationDate;
use App\Incentive\Domain\User\ValueObject\ExpiringUserBonusPoints;
use App\Incentive\Domain\User\ValueObject\UserBonusPoints;
use App\Incentive\Domain\User\ValueObject\UserId;
use App\Incentive\Domain\User\ValueObject\UserName;
use App\Tests\Incentive\Domain\User\CommandHandler\RideShareCompleteHandlerTest;
use Carbon\Carbon;

class BoosterRideShareTest extends RideShareCompleteHandlerTest
{
    /**
     * @test
     */
    public function it_applies_booster_for_applicable_deliveries()
    {
        Carbon::setTestNow('2022-09-01 21:30:00');

        $userId = '00000000-0000-0000-0000-000000000000';
        $username = 'username';
        $userEmailAddress = 'user@domain.com';

        $rideShareId1 = '00000000-0000-0000-0000-000000000001';
        $rideShareCompletedAt1 = Carbon::create('2022-09-01 21:00:00');
        $rideShareBonusPoints1 = 2;

        $rideShareId2 = '00000000-0000-0000-0000-000000000002';
        $rideShareCompletedAt2 = Carbon::create('2022-09-01 21:30:00');
        $rideShareBonusPoints2 = 2;

        $boosterId = '00000000-0000-0000-0000-000000000003';
        $boosterAppliedAt = Carbon::create('2022-09-01 19:00:00');
        $boosterActiveFrom = Carbon::create('2022-09-01 20:00:00');
        $boosterActiveTo = Carbon::create('2022-09-01 22:00:00');
        $applicableForAction = 'rideShare';
        $boosterBonusPoints = 5;
        $boosterBonusPointsValidForDays = 15;
        $boosterActionsRequired = 2;

        $this->scenario
            ->withAggregateId($userId)
            ->given([
                new UserWasRegistered(
                    UserId::fromString($userId),
                    UserName::fromString($username),
                    EmailAddress::fromString($userEmailAddress)
                ),
                new BoosterActivated(
                    UserId::fromString($userId),
                    BoosterId::fromString($boosterId),
                    BoosterAppliedAt::fromString($boosterAppliedAt),
                    BoosterActiveRange::fromActiveFromAndActiveTo(
                        BoosterActiveFrom::fromString($boosterActiveFrom),
                        BoosterActiveTo::fromString($boosterActiveTo)
                    ),
                    BoosterApplicableForAction::fromString($applicableForAction),
                    BoosterBonusPoints::fromIntAndExpireAt(
                        $boosterBonusPoints,
                        BoosterBonusPointsValidFor::fromDaysAsInt($boosterBonusPointsValidForDays)
                    ),
                    BoosterActionsRequired::fromInt($boosterActionsRequired)
                ),
                new RideShareCompleted(
                    UserId::fromString($userId),
                    ActionId::fromString($rideShareId1),
                    CompletedAt::fromString($rideShareCompletedAt1),
                    ActionBonusPoints::fromInt($rideShareBonusPoints1)
                )
            ])
            ->when(new RideShareComplete($userId, $rideShareId2, $rideShareCompletedAt2))
            ->then([
                new RideShareCompleted(
                    UserId::fromString($userId),
                    ActionId::fromString($rideShareId2),
                    CompletedAt::fromString($rideShareCompletedAt2),
                    ActionBonusPoints::fromInt($rideShareBonusPoints2)
                ),
                new BoosterApplied(
                    UserId::fromString($userId),
                    ExpiringUserBonusPoints::fromUserBonusPointsAndExpirationDate(
                        UserBonusPoints::fromInt($boosterBonusPoints),
                        ExpirationDate::fromBoosterBonusPoints(
                            BoosterBonusPoints::fromIntAndExpireAt(
                                $boosterBonusPoints,
                                BoosterBonusPointsValidFor::fromDaysAsInt($boosterBonusPointsValidForDays)
                            )
                        )
                    ),
                    BoosterApplicableForAction::fromString($applicableForAction),
                )
            ]);
    }

    /**
     * @test
     */
    public function it_does_not_apply_booster_for_applicable_deliveries_from_past()
    {
        Carbon::setTestNow('2022-09-01 21:30:00');

        $userId = '00000000-0000-0000-0000-000000000000';
        $username = 'username';
        $userEmailAddress = 'user@domain.com';

        $rideShareId1 = '00000000-0000-0000-0000-000000000001';
        $rideShareCompletedAt1 = Carbon::create('2022-09-01 21:0:00');
        $rideShareBonusPoints1 = 2;

        $rideShareId2 = '00000000-0000-0000-0000-000000000002';
        $rideShareCompletedAt2 = Carbon::create('2022-09-01 21:30:00');
        $rideShareBonusPoints2 = 2;

        $boosterId = '00000000-0000-0000-0000-000000000003';
        $boosterAppliedAt = Carbon::create('2022-09-01 21:00:00');
        $boosterActiveFrom = Carbon::create('2022-09-01 21:00:00');
        $boosterActiveTo = Carbon::create('2022-09-01 22:00:00');
        $applicableForAction = 'rideShare';
        $boosterBonusPoints = 5;
        $boosterBonusPointsValidForDays = 15;
        $boosterActionsRequired = 2;

        $this->scenario
            ->withAggregateId($userId)
            ->given([
                new UserWasRegistered(
                    UserId::fromString($userId),
                    UserName::fromString($username),
                    EmailAddress::fromString($userEmailAddress)
                ),
                new RideShareCompleted(
                    UserId::fromString($userId),
                    ActionId::fromString($rideShareId1),
                    CompletedAt::fromString($rideShareCompletedAt1),
                    ActionBonusPoints::fromInt($rideShareBonusPoints1)
                ),
                new BoosterActivated(
                    UserId::fromString($userId),
                    BoosterId::fromString($boosterId),
                    BoosterAppliedAt::fromString($boosterAppliedAt),
                    BoosterActiveRange::fromActiveFromAndActiveTo(
                        BoosterActiveFrom::fromString($boosterActiveFrom),
                        BoosterActiveTo::fromString($boosterActiveTo)
                    ),
                    BoosterApplicableForAction::fromString($applicableForAction),
                    BoosterBonusPoints::fromIntAndExpireAt(
                        $boosterBonusPoints,
                        BoosterBonusPointsValidFor::fromDaysAsInt($boosterBonusPointsValidForDays)
                    ),
                    BoosterActionsRequired::fromInt($boosterActionsRequired)
                ),
            ])
            ->when(new RideShareComplete($userId, $rideShareId2, $rideShareCompletedAt2))
            ->then([
                new RideShareCompleted(
                    UserId::fromString($userId),
                    ActionId::fromString($rideShareId2),
                    CompletedAt::fromString($rideShareCompletedAt2),
                    ActionBonusPoints::fromInt($rideShareBonusPoints2)
                )
            ]);
    }
}
