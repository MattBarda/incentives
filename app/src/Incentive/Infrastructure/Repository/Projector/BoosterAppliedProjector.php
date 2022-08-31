<?php

namespace App\Incentive\Infrastructure\Repository\Projector;

use App\Incentive\Domain\User\DomainEvent\BoosterApplied;
use App\Incentive\Domain\User\Repository\ReadModel\UserPoints;
use App\Incentive\Domain\User\Repository\UserPointsRepositoryInterface;
use Broadway\ReadModel\Projector;

class BoosterAppliedProjector extends Projector
{
    private UserPointsRepositoryInterface $userPointsRepository;

    public function __construct(UserPointsRepositoryInterface $userPointsRepository)
    {
        $this->userPointsRepository = $userPointsRepository;
    }

    public function applyBoosterApplied(BoosterApplied $event): void
    {
        $userPoints = new UserPoints(
            $event->userId()->toString(),
            0,
        );
        $userPoints->setExpiringPointsFromArray([
            'points' => $event->expiringUserBonusPoints()->userBonusPoints()->toInt(),
            'expireAt' => $event->expiringUserBonusPoints()->expirationDate()->toString()
        ]);
        $this->userPointsRepository->save($userPoints);
    }
}
