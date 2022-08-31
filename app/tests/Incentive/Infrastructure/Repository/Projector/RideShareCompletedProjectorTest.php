<?php

namespace App\Tests\Incentive\Infrastructure\Repository\Projector;

use App\Incentive\Domain\User\DomainEvent\RideShareCompleted;
use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\CompletedAt;
use App\Incentive\Domain\User\ValueObject\UserId;
use App\Incentive\Infrastructure\Repository\InMemoryRepository\UserPointsInMemoryRepository;
use App\Incentive\Infrastructure\Repository\Projector\RideShareCompletedProjector;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class RideShareCompletedProjectorTest extends TestCase
{
    public function test_it_saves_correct_numer_of_points_when_ride_share_has_completed()
    {
        $userId = '00000000-0000-0000-0000-000000000000';
        $rideShareId = '00000000-0000-0000-0000-000000000001';
        $rideShareCompletedAt = Carbon::create('2022-08-27 20:00:00');
        $rideShareBonusPoints = 2;

        $userPointsInMemoryRepository = new UserPointsInMemoryRepository();
        $userPointsInMemoryRepository->init($userId);
        $sut = new RideShareCompletedProjector($userPointsInMemoryRepository);

        $sut->applyRideShareCompleted(
            new RideShareCompleted(
                UserId::fromString($userId),
                ActionId::fromString($rideShareId),
                CompletedAt::fromString($rideShareCompletedAt),
                ActionBonusPoints::fromInt($rideShareBonusPoints)
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
