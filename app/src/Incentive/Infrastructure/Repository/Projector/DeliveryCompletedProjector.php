<?php

namespace App\Incentive\Infrastructure\Repository\Projector;

use App\Incentive\Domain\User\DomainEvent\DeliveryCompleted;
use App\Incentive\Domain\User\Repository\ReadModel\UserPoints;
use App\Incentive\Domain\User\Repository\UserPointsRepositoryInterface;
use Broadway\ReadModel\Projector;

class DeliveryCompletedProjector extends Projector
{
    private UserPointsRepositoryInterface $userPointsRepository;

    public function __construct(UserPointsRepositoryInterface $userPointsRepository)
    {
        $this->userPointsRepository = $userPointsRepository;
    }

    protected function applyDeliveryCompleted(DeliveryCompleted $event): void
    {
        $userPoints = new UserPoints(
            $event->userId()->toString(),
            $event->actionBonusPoints()->toInt()
        );
        $this->userPointsRepository->save($userPoints);
    }
}
