<?php

namespace App\Tests\Incentive\Domain\User\DomainEvent;

use App\Incentive\Domain\User\DomainEvent\RentStarted;
use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\StartedAt;
use App\Incentive\Domain\User\ValueObject\UserId;
use Broadway\Serializer\Testing\SerializableEventTestCase;
use Carbon\Carbon;

class RentStartedTest extends SerializableEventTestCase
{
    /**
     * @test
     */
    public function getter_of_event_work()
    {
        $userId = '00000000-0000-0000-0000-000000000000';
        $rentId = '00000000-0000-0000-0000-000000000001';
        $rentStartedAt = Carbon::create('2022-08-27 20:00:00');

        $event = new RentStarted(
            UserId::fromString($userId),
            ActionId::fromString($rentId),
            StartedAt::fromString($rentStartedAt),
        );

        $this->assertEquals($userId, $event->userId()->toString());
        $this->assertEquals($rentId, $event->rentId()->toString());
        $this->assertEquals($rentStartedAt, $event->startedAt()->toString());
    }

    protected function createEvent()
    {
        $userId = '00000000-0000-0000-0000-000000000000';
        $rentId = '00000000-0000-0000-0000-000000000001';
        $rentStartedAt = Carbon::create('2022-08-27 20:00:00');

        return new RentStarted(
            UserId::fromString($userId),
            ActionId::fromString($rentId),
            StartedAt::fromString($rentStartedAt),
        );
    }
}
