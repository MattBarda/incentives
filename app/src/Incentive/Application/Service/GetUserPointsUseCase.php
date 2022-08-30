<?php

namespace App\Incentive\Application\Service;

use App\Incentive\Application\DTO\GetUserPoints;
use App\Incentive\Domain\User\Repository\UserPointsRepositoryInterface;

class GetUserPointsUseCase 
{
    private UserPointsRepositoryInterface $userPointsRepository;

    public function __construct(UserPointsRepositoryInterface $userPointsRepository)
    {
        $this->userPointsRepository = $userPointsRepository;
    }

    public function execute(GetUserPoints $getUserPoints): int
    {
        return $this->userPointsRepository->getPointsForDate(
            $getUserPoints->getUserId(),
            $getUserPoints->getPointsForDate()
        );
    }
}
