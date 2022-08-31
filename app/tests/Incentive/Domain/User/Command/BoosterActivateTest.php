<?php

namespace App\Tests\Incentive\Domain\User\Command;

use App\Incentive\Domain\User\Command\BoosterActivate;
use App\Incentive\Domain\User\DomainEvent\BoosterActivated;
use App\Incentive\Domain\User\DomainEvent\UserWasRegistered;
use App\Incentive\Domain\User\ValueObject\BoosterActionsRequired;
use App\Incentive\Domain\User\ValueObject\BoosterActiveFrom;
use App\Incentive\Domain\User\ValueObject\BoosterActiveRange;
use App\Incentive\Domain\User\ValueObject\BoosterActiveTo;
use App\Incentive\Domain\User\ValueObject\BoosterApplicableForAction;
use App\Incentive\Domain\User\ValueObject\BoosterAppliedAt;
use App\Incentive\Domain\User\ValueObject\BoosterBonusPoints;
use App\Incentive\Domain\User\ValueObject\BoosterBonusPointsValidFor;
use App\Incentive\Domain\User\ValueObject\BoosterId;
use App\Incentive\Domain\User\ValueObject\EmailAddress;
use App\Incentive\Domain\User\ValueObject\UserId;
use App\Incentive\Domain\User\ValueObject\UserName;
use App\Tests\Incentive\Domain\User\CommandHandler\BoosterActivateHandlerTest;
use Carbon\Carbon;

class BoosterActivateTest extends BoosterActivateHandlerTest
{
    /**
     * @test
     */
    public function it_activates_booster()
    {
        $userId = '00000000-0000-0000-0000-000000000000';
        $username = 'username';
        $userEmailAddress = 'user@domain.com';

        $boosterId = '00000000-0000-0000-0000-000000000001';
        $boosterAppliedAt = Carbon::create('2022-08-27 20:00:00');
        $boosterValidFrom = Carbon::create('2022-08-27 20:00:00');
        $boosterValidTo = Carbon::create('2022-08-27 22:00:00');
        $applicableForAction = 'delivery';
        $boosterBonusPoints = 5;
        $boosterBonusPointsValidForDays = 15;
        $boosterActionsRequired = 4;

        $this->scenario
            ->withAggregateId($userId)
            ->given([
                new UserWasRegistered(
                    UserId::fromString($userId),
                    UserName::fromString($username),
                    EmailAddress::fromString($userEmailAddress)
                ),
            ])
            ->when(new BoosterActivate(
                    $userId,
                    $boosterId,
                    $boosterAppliedAt,
                    $boosterValidFrom,
                    $boosterValidTo,
                    $applicableForAction,
                    $boosterBonusPoints,
                    $boosterBonusPointsValidForDays,
                    $boosterActionsRequired
                    )
                )
            ->then([
                new BoosterActivated(
                    UserId::fromString($userId),
                    BoosterId::fromString($boosterId),
                    BoosterAppliedAt::fromString($boosterAppliedAt),
                    BoosterActiveRange::fromActiveFromAndActiveTo(
                        BoosterActiveFrom::fromString($boosterValidFrom),
                        BoosterActiveTo::fromString($boosterValidTo)
                    ),
                    BoosterApplicableForAction::fromString($applicableForAction),
                    BoosterBonusPoints::fromIntAndExpireAt(
                        $boosterBonusPoints,
                        BoosterBonusPointsValidFor::fromDaysAsInt($boosterBonusPointsValidForDays)
                    ),
                    BoosterActionsRequired::fromInt($boosterActionsRequired)
                ),
            ]);
    }


}
