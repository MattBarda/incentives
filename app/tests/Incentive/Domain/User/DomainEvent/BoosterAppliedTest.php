<?php

namespace App\Tests\Incentive\Domain\User\DomainEvent;

use App\Incentive\Domain\User\DomainEvent\BoosterApplied;
use App\Incentive\Domain\User\ValueObject\BoosterApplicableForAction;
use App\Incentive\Domain\User\ValueObject\BoosterBonusPoints;
use App\Incentive\Domain\User\ValueObject\BoosterBonusPointsValidFor;
use App\Incentive\Domain\User\ValueObject\ExpirationDate;
use App\Incentive\Domain\User\ValueObject\ExpiringUserBonusPoints;
use App\Incentive\Domain\User\ValueObject\UserBonusPoints;
use App\Incentive\Domain\User\ValueObject\UserId;
use Broadway\Serializer\Testing\SerializableEventTestCase;
use Carbon\Carbon;

class BoosterAppliedTest extends SerializableEventTestCase
{
    /**
     * @test
     */
    public function getter_of_event_work()
    {
        Carbon::setTestNow('2022-09-01 21:30:00');
        //Expected
        $userId = '00000000-0000-0000-0000-000000000003';
        $applicableForAction = 'delivery';
        $boosterBonusPoints = 5;
        $boosterBonusPointsValidForDays = 15;
        $boosterBonusPointsExpireAt = '2022-09-16 21:30:00';

        $event = new BoosterApplied(
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
        );

        $this->assertEquals($userId, $event->userId()->toString());
        $this->assertEquals($applicableForAction, $event->action()->toString());
        $this->assertEquals($boosterBonusPoints, $event->expiringUserBonusPoints()->userBonusPoints()->toInt());
        $this->assertEquals($boosterBonusPointsExpireAt, $event->expiringUserBonusPoints()->expirationDate()->toString());
    }

    protected function createEvent()
    {
        //Provided
        $userId = '00000000-0000-0000-0000-000000000003';
        $applicableForAction = 'delivery';
        $boosterBonusPoints = 5;
        $boosterBonusPointsValidForDays = 15;

        return new BoosterApplied(
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
        );
    }
}
