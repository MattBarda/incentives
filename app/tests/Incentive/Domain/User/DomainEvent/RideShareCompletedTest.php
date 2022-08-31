<?php

namespace App\Tests\Incentive\Domain\User\DomainEvent;

use App\Incentive\Domain\User\DomainEvent\RideShareCompleted;
use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\CompletedAt;
use App\Incentive\Domain\User\ValueObject\UserId;
use Broadway\Serializer\Testing\SerializableEventTestCase;
use Carbon\Carbon;

class RideShareCompletedTest extends SerializableEventTestCase
{
    /**
     * @test
     */
    public function getter_of_event_work()
    {
        $userId = '00000000-0000-0000-0000-000000000000';
        $rideShareId = '00000000-0000-0000-0000-000000000001';
        $rideShareCompletedAt = Carbon::create('2022-08-27 20:00:00');
        $rideShareBonusPoints = 2;

        $event = new RideShareCompleted(
            UserId::fromString($userId),
            ActionId::fromString($rideShareId),
            CompletedAt::fromString($rideShareCompletedAt),
            ActionBonusPoints::fromInt($rideShareBonusPoints)
        );

        $this->assertEquals($userId, $event->userId()->toString());
        $this->assertEquals($rideShareId, $event->rideShareId()->toString());
        $this->assertEquals($rideShareCompletedAt, $event->completedAt()->toString());
        $this->assertEquals($rideShareBonusPoints, $event->actionBonusPoints()->toInt());
    }

    protected function createEvent()
    {
        $userId = '00000000-0000-0000-0000-000000000000';
        $rideShareId = '00000000-0000-0000-0000-000000000001';
        $rideShareCompletedAt = Carbon::create('2022-08-27 20:00:00');
        $rideShareBonusPoints = 2;

        return new RideShareCompleted(
            UserId::fromString($userId),
            ActionId::fromString($rideShareId),
            CompletedAt::fromString($rideShareCompletedAt),
            ActionBonusPoints::fromInt($rideShareBonusPoints)
        );
    }
}
