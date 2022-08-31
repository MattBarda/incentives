<?php

namespace App\Incentive\Infrastructure\Repository\InMemoryRepository;

use App\Incentive\Domain\User\Repository\ReadModel\UserPoints;
use App\Incentive\Domain\User\Repository\UserPointsRepositoryInterface;
use Carbon\Carbon;

class UserPointsInMemoryRepository implements UserPointsRepositoryInterface
{
    private $userPoints = [];

    public function save(UserPoints $userPoints): void
    {
        $savedUserPointsValue = $this->userPoints[$userPoints->getUserId()]['points'];
        $savedExpiringPoints = $this->userPoints[$userPoints->getUserId()]['expiringPoints'];

        $this->userPoints[$userPoints->getUserId()] = [
            'userId' => $userPoints->getUserId(),
            'points' => $savedUserPointsValue + $userPoints->getPoints(),
            'expiringPoints' => array_merge(
                $savedExpiringPoints,
                $userPoints->getExpiringPointsAsArray()
            )
        ];
    }

    public function getPointsForDate(string $userId, Carbon $date): int
    {
        $savedUserPointsValue = $this->userPoints[$userId]['points'];
        $savedExpiringPoints = $this->userPoints[$userId]['expiringPoints'];

        $filtered = array_filter($savedExpiringPoints, static function($points) use ($date) {
            return $date->isBefore(Carbon::create($points['expireAt']));
        });

        foreach ($filtered as $points) {
            $savedUserPointsValue += $points['points'];
        }

        return $savedUserPointsValue;
    }

    public function init(string $userId): void
    {
        $this->userPoints[$userId] = [
            'userId' => $userId,
            'points' => 0,
            'expiringPoints' => []
        ];
    }
}
