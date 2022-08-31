<?php

namespace App\Tests\Incentive\Infrastructure\Repository\Projector;

use App\Incentive\Domain\User\DomainEvent\DeliveryCompleted;
use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\CompletedAt;
use App\Incentive\Domain\User\ValueObject\UserId;
use App\Incentive\Infrastructure\Repository\InMemoryRepository\UserPointsInMemoryRepository;
use App\Incentive\Infrastructure\Repository\Projector\DeliveryCompletedProjector;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class DeliveryCompletedProjectorTest extends TestCase
{
    public function test_it_saves_correct_numer_of_points_when_delivery_has_completed()
    {
        $userId = '00000000-0000-0000-0000-000000000000';
        $deliveryId = '00000000-0000-0000-0000-000000000001';
        $deliveryCompletedAt = Carbon::create('2022-08-27 20:00:00');
        $deliveryBonusPoints = 2;

        $userPointsInMemoryRepository = new UserPointsInMemoryRepository();
        $userPointsInMemoryRepository->init($userId);
        $sut = new DeliveryCompletedProjector($userPointsInMemoryRepository);

        $sut->applyDeliveryCompleted(
            new DeliveryCompleted(
                UserId::fromString($userId),
                ActionId::fromString($deliveryId),
                CompletedAt::fromString($deliveryCompletedAt),
                ActionBonusPoints::fromInt($deliveryBonusPoints)
            )
        );

        $this->assertEquals(
            2,
            $userPointsInMemoryRepository->getPointsForDate(
                $userId,
                Carbon::create('2022-08-27 21:00:00')
            )
        );
    }
}
