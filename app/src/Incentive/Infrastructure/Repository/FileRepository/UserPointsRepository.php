<?php

namespace App\Incentive\Infrastructure\Repository\FileRepository;

use App\Incentive\Domain\User\Exception\RepositoryFileNotFoundException;
use App\Incentive\Domain\User\Repository\ReadModel\UserPoints;
use App\Incentive\Domain\User\Repository\UserPointsRepositoryInterface;
use Carbon\Carbon;

class UserPointsRepository implements UserPointsRepositoryInterface
{
    const FILE_PREFIX = 'user-';

    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function save(UserPoints $userPoints)
    {
        $fileName = $this->filePath . self::FILE_PREFIX . $userPoints->getUserId() . '.json';
        $savedUserPoints = $this->getSavedUserPointsFrom($fileName);

        $savedUserPointsValue = $savedUserPoints[$userPoints->getUserId()]['points'];
        $savedExpiringPoints = $savedUserPoints[$userPoints->getUserId()]['expiringPoints'];

        $newUserPoints[$userPoints->getUserId()] = [
            'userId' => $userPoints->getUserId(),
            'points' => $savedUserPointsValue + $userPoints->getPoints(),
            'expiringPoints' => array_merge(
                $savedExpiringPoints,
                $userPoints->getExpiringPointsAsArray()
            )
        ];

        file_put_contents($fileName, json_encode($newUserPoints));
    }

    public function getPointsForDate(string $userId, Carbon $date): int
    {
        $fileName = $this->filePath . self::FILE_PREFIX . $userId . '.json';

        $savedUserPoints = $this->getSavedUserPointsFrom($fileName);
        $savedUserPointsValue = $savedUserPoints[$userId]['points'];
        $savedExpiringPoints = $savedUserPoints[$userId]['expiringPoints'];

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
        $fileName = $this->filePath . 'user-' . $userId . '.json';
        $user[$userId] = [
            'userId' => $userId,
            'points' => 0,
            'expiringPoints' => []
        ];

        file_put_contents($fileName, json_encode($user));
    }

    private function getSavedUserPointsFrom(string $fileName): array
    {
        if (!file_exists($fileName)) {
            throw new RepositoryFileNotFoundException($fileName);
        }

        $savedUserPoints = json_decode(
            file_get_contents($fileName),
            true
        );
        return $savedUserPoints;
    }
}
