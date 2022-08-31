<?php

namespace App\Tests\Incentive\Domain\User\DomainEvent;

use App\Incentive\Domain\User\DomainEvent\BoosterActivated;
use App\Incentive\Domain\User\ValueObject\BoosterActionsRequired;
use App\Incentive\Domain\User\ValueObject\BoosterActiveFrom;
use App\Incentive\Domain\User\ValueObject\BoosterActiveRange;
use App\Incentive\Domain\User\ValueObject\BoosterActiveTo;
use App\Incentive\Domain\User\ValueObject\BoosterApplicableForAction;
use App\Incentive\Domain\User\ValueObject\BoosterAppliedAt;
use App\Incentive\Domain\User\ValueObject\BoosterBonusPoints;
use App\Incentive\Domain\User\ValueObject\BoosterBonusPointsValidFor;
use App\Incentive\Domain\User\ValueObject\BoosterId;
use App\Incentive\Domain\User\ValueObject\UserId;
use Broadway\Serializer\Testing\SerializableEventTestCase;
use Carbon\Carbon;

class BoosterActivatedTest extends SerializableEventTestCase
{
    /**
     * @test
     */
    public function getter_of_event_work()
    {
        //Expected
        $userId = '00000000-0000-0000-0000-000000000000';
        $boosterId = '00000000-0000-0000-0000-000000000001';
        $boosterAppliedAt = Carbon::create('2022-08-27 20:00:00');
        $boosterActiveFrom = Carbon::create('2022-08-27 20:00:00');
        $boosterActiveTo = Carbon::create('2022-08-27 22:00:00');
        $applicableForAction = 'delivery';
        $boosterBonusPoints = 5;
        $boosterBonusPointsValidForDays = 15;
        $boosterActionsRequired = 4;

        $event = new BoosterActivated(
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
        );

        $this->assertEquals($userId, $event->userId()->toString());
        $this->assertEquals($boosterId, $event->boosterId()->toString());
        $this->assertEquals($boosterAppliedAt, $event->appliedAt()->toString());
        $this->assertEquals($boosterActiveFrom, $event->activeRange()->boosterActiveFrom()->toString());
        $this->assertEquals($boosterActiveTo, $event->activeRange()->boosterActiveTo()->toString());
        $this->assertEquals($applicableForAction, $event->applicableForAction()->toString());
        $this->assertEquals($boosterBonusPoints, $event->boosterBonusPoints()->pointsAsInt());
        $this->assertEquals($boosterBonusPointsValidForDays, $event->boosterBonusPoints()->boosterBonusPointsValidFor()->toInt());
        $this->assertEquals($boosterActionsRequired, $event->boosterActionsRequired()->toInt());
    }

    protected function createEvent()
    {
        //Provided
        $userId = '00000000-0000-0000-0000-000000000000';
        $boosterId = '00000000-0000-0000-0000-000000000001';
        $boosterAppliedAt = Carbon::create('2022-08-27 20:00:00');
        $boosterActiveFrom = Carbon::create('2022-08-27 20:00:00');
        $boosterActiveTo = Carbon::create('2022-08-27 22:00:00');
        $applicableForAction = 'delivery';
        $boosterBonusPoints = 5;
        $boosterBonusPointsValidForDays = 15;
        $boosterActionsRequired = 4;

        return new BoosterActivated(
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
        );
    }
}