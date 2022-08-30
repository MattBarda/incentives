<?php

namespace App\Incentive\Infrastructure\Repository\Projector;

use App\Incentive\Domain\User\DomainEvent\UserWasRegistered;
use App\Incentive\Domain\User\Repository\UserPointsRepositoryInterface;
use Broadway\ReadModel\Projector;

class UserWasRegisteredProjector extends Projector
{
    private UserPointsRepositoryInterface $userPointsRepository;

    public function __construct(UserPointsRepositoryInterface $userPointsRepository)
    {
        $this->userPointsRepository = $userPointsRepository;
    }

    protected function applyUserWasRegistered(UserWasRegistered $event)
    {
        $this->userPointsRepository->init($event->userId()->toString());
    }
}
