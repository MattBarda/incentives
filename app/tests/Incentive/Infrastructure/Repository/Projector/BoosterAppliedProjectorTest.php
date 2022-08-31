<?php

namespace App\Tests\Incentive\Infrastructure\Repository\Projector;

use App\Incentive\Domain\User\DomainEvent\BoosterApplied;
use App\Incentive\Domain\User\ValueObject\BoosterApplicableForAction;
use App\Incentive\Domain\User\ValueObject\BoosterBonusPoints;
use App\Incentive\Domain\User\ValueObject\BoosterBonusPointsValidFor;
use App\Incentive\Domain\User\ValueObject\ExpirationDate;
use App\Incentive\Domain\User\ValueObject\ExpiringUserBonusPoints;
use App\Incentive\Domain\User\ValueObject\UserBonusPoints;
use App\Incentive\Domain\User\ValueObject\UserId;
use App\Incentive\Infrastructure\Repository\InMemoryRepository\UserPointsInMemoryRepository;
use App\Incentive\Infrastructure\Repository\Projector\BoosterAppliedProjector;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class BoosterAppliedProjectorTest extends TestCase
{
    public function test_it_saves_correct_numer_of_expiring_points_when_booster_was_applied()
    {
        Carbon::setTestNow('2022-09-01 21:30:00');

        $userId = '00000000-0000-0000-0000-000000000000';
        $applicableForAction = 'delivery';
        $boosterBonusPoints = 5;
        $boosterBonusPointsValidForDays = 15;

        $userPointsInMemoryRepository = new UserPointsInMemoryRepository();
        $userPointsInMemoryRepository->init($userId);
        $sut = new BoosterAppliedProjector($userPointsInMemoryRepository);

        $sut->applyBoosterApplied(new BoosterApplied(
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
        ));

        $this->assertEquals(
            5,
            $userPointsInMemoryRepository->getPointsForDate(
                $userId,
                Carbon::create('2022-09-02 21:00:00')
            )
        );
        $this->assertEquals(
            0,
            $userPointsInMemoryRepository->getPointsForDate(
                $userId,
                Carbon::create('2022-09-17 21:00:00')
            )
        );
    }
}
