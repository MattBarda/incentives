<?php

namespace App\Tests\Incentive\Domain\User\DomainEvent;

use App\Incentive\Domain\User\DomainEvent\RentCompleted;
use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\CompletedAt;
use App\Incentive\Domain\User\ValueObject\RentDuration;
use App\Incentive\Domain\User\ValueObject\StartedAt;
use App\Incentive\Domain\User\ValueObject\UserId;
use Broadway\Serializer\Testing\SerializableEventTestCase;
use Carbon\Carbon;

class RentCompletedTest extends SerializableEventTestCase
{
    /**
     * @test
     */
    public function getter_of_event_work()
    {
        $userId = '00000000-0000-0000-0000-000000000000';
        $rentId = '00000000-0000-0000-0000-000000000001';
        $rentStartedAt = Carbon::create('2022-08-14 20:00:00');
        $rentCompletedAt = Carbon::create('2022-08-15 20:00:01');
        $rentBonusPointsPerDay = 2;
        $rentBonusPoints = 2;

        $event = new RentCompleted(
            UserId::fromString($userId),
            ActionId::fromString($rentId),
            ActionBonusPoints::fromInt($rentBonusPointsPerDay),
            RentDuration::fromStartedAtAndCompletedAt(
                StartedAt::fromString($rentStartedAt),
                CompletedAt::fromString($rentCompletedAt)
            ),
            ActionBonusPoints::fromInt($rentBonusPoints)
        );

        $this->assertEquals($userId, $event->userId()->toString());
        $this->assertEquals($rentId, $event->rentId()->toString());
        $this->assertEquals($rentStartedAt, $event->rentDuration()->startedAt()->toString());
        $this->assertEquals($rentCompletedAt, $event->rentDuration()->completedAt()->toString());
        $this->assertEquals($rentBonusPointsPerDay, $event->actionBonusPointsPerDay()->toInt());
        $this->assertEquals($rentBonusPoints, $event->actionBonusPoints()->toInt());
    }


    protected function createEvent()
    {
        $userId = '00000000-0000-0000-0000-000000000000';
        $rentId = '00000000-0000-0000-0000-000000000001';
        $rentStartedAt = Carbon::create('2022-08-14 20:00:00');
        $rentCompletedAt = Carbon::create('2022-08-15 20:00:01');
        $rentBonusPointsPerDay = 2;
        $rentBonusPoints = 2;

        return new RentCompleted(
            UserId::fromString($userId),
            ActionId::fromString($rentId),
            ActionBonusPoints::fromInt($rentBonusPointsPerDay),
            RentDuration::fromStartedAtAndCompletedAt(
                StartedAt::fromString($rentStartedAt),
                CompletedAt::fromString($rentCompletedAt)
            ),
            ActionBonusPoints::fromInt($rentBonusPoints)
        );
    }
}
