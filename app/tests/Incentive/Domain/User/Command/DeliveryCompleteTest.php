<?php

namespace App\Tests\Incentive\Domain\User\Command;

use App\Incentive\Domain\User\Command\DeliveryComplete;
use App\Incentive\Domain\User\DomainEvent\DeliveryCompleted;
use App\Incentive\Domain\User\DomainEvent\UserWasRegistered;
use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\CompletedAt;
use App\Incentive\Domain\User\ValueObject\EmailAddress;
use App\Incentive\Domain\User\ValueObject\UserId;
use App\Incentive\Domain\User\ValueObject\UserName;
use App\Tests\Incentive\Domain\User\CommandHandler\DeliveryCompleteHandlerTest;
use Carbon\Carbon;

class DeliveryCompleteTest extends DeliveryCompleteHandlerTest
{
    /**
     * @test
     */
    public function it_completes_a_delivery()
    {
        $userId = '00000000-0000-0000-0000-000000000000';
        $username = 'username';
        $userEmailAddress = 'user@domain.com';

        $deliveryId = '00000000-0000-0000-0000-000000000001';
        $deliveryCompletedAt = Carbon::create('2022-08-27 20:00:00');
        $deliveryBonusPoints = 2;

        $this->scenario
            ->withAggregateId($userId)
            ->given([
                new UserWasRegistered(
                    UserId::fromString($userId),
                    UserName::fromString($username),
                    EmailAddress::fromString($userEmailAddress)
                ),
            ])
            ->when(new DeliveryComplete($userId, $deliveryId, $deliveryCompletedAt))
            ->then([
                new DeliveryCompleted(
                    UserId::fromString($userId),
                    ActionId::fromString($deliveryId),
                    CompletedAt::fromString($deliveryCompletedAt),
                    ActionBonusPoints::fromInt($deliveryBonusPoints)
                ),
            ]);
    }
}
