<?php

namespace App\Tests\Incentive\Infrastructure\Repository\Projector;

use App\Incentive\Domain\User\DomainEvent\RentCompleted;
use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\CompletedAt;
use App\Incentive\Domain\User\ValueObject\RentDuration;
use App\Incentive\Domain\User\ValueObject\StartedAt;
use App\Incentive\Domain\User\ValueObject\UserId;
use App\Incentive\Infrastructure\Repository\InMemoryRepository\UserPointsInMemoryRepository;
use App\Incentive\Infrastructure\Repository\Projector\RentCompletedProjector;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class RentCompletedProjectorTest extends TestCase
{
    public function test_it_saves_correct_numer_of_points_when_rent_has_completed()
    {
        $userId = '00000000-0000-0000-0000-000000000000';
        $rentId = '00000000-0000-0000-0000-000000000001';
        $rentStartedAt = Carbon::create('2022-08-10 20:00:00');
        $rentCompletedAt = Carbon::create('2022-08-15 20:00:01');
        $rentBonusPointsPerDay = 2;
        $rentBonusPoints = 10;

        $userPointsInMemoryRepository = new UserPointsInMemoryRepository();
        $userPointsInMemoryRepository->init($userId);
        $sut = new RentCompletedProjector($userPointsInMemoryRepository);

        $sut->applyRentCompleted(
            new  RentCompleted(
                UserId::fromString($userId),
                ActionId::fromString($rentId),
                ActionBonusPoints::fromInt($rentBonusPointsPerDay),
                RentDuration::fromStartedAtAndCompletedAt(
                    StartedAt::fromString($rentStartedAt),
                    CompletedAt::fromString($rentCompletedAt)
                ),
                ActionBonusPoints::fromInt($rentBonusPoints)
            )
        );

        $this->assertEquals(
            10,
            $userPointsInMemoryRepository->getPointsForDate(
                $userId,
                Carbon::create('2022-08-27 21:00:00')
            )
        );
    }
}
