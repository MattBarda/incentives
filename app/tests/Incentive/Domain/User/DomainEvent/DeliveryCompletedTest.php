<?php

namespace App\Tests\Incentive\Domain\User\DomainEvent;

use App\Incentive\Domain\User\DomainEvent\DeliveryCompleted;
use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\CompletedAt;
use App\Incentive\Domain\User\ValueObject\UserId;
use Broadway\Serializer\Testing\SerializableEventTestCase;
use Carbon\Carbon;

class DeliveryCompletedTest extends SerializableEventTestCase
{
    /**
     * @test
     */
    public function getter_of_event_work()
    {
        $userId = '00000000-0000-0000-0000-000000000000';
        $deliveryId = '00000000-0000-0000-0000-000000000001';
        $deliveryCompletedAt = Carbon::create('2022-08-27 20:00:00');
        $deliveryBonusPoints = 2;

        $event = new DeliveryCompleted(
            UserId::fromString($userId),
            ActionId::fromString($deliveryId),
            CompletedAt::fromString($deliveryCompletedAt),
            ActionBonusPoints::fromInt($deliveryBonusPoints)
        );

        $this->assertEquals($userId, $event->userId()->toString());
        $this->assertEquals($deliveryId, $event->deliveryId()->toString());
        $this->assertEquals($deliveryCompletedAt, $event->completedAt()->toString());
        $this->assertEquals($deliveryBonusPoints, $event->actionBonusPoints()->toInt());
    }

    protected function createEvent()
    {
        $userId = '00000000-0000-0000-0000-000000000000';
        $deliveryId = '00000000-0000-0000-0000-000000000001';
        $deliveryCompletedAt = Carbon::create('2022-08-27 20:00:00');
        $deliveryBonusPoints = 2;

        return new DeliveryCompleted(
            UserId::fromString($userId),
            ActionId::fromString($deliveryId),
            CompletedAt::fromString($deliveryCompletedAt),
            ActionBonusPoints::fromInt($deliveryBonusPoints)
        );
    }
}
