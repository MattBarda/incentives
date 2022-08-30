<?php
namespace App\Incentive\Domain\User\Repository\ReadModel;

class UserPoints
{
    private string $userId;
    private int $points;
    /** @var ExpiringPoints[] */
    private array $expiringPointsArray;

    public function __construct(string $userId, int $points, array $expiringPointsArray = [])
    {
        $this->userId = $userId;
        $this->points = $points;
        $this->expiringPointsArray = $expiringPointsArray;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function getExpiringPointsAsArray(): array
    {
        $result = [];
        foreach ($this->expiringPointsArray as $points) {
            $result[] = $points->toArray();
        }
        return $result;
    }

    public function setExpiringPointsFromArray(array $expiringPoints)
    {
        $this->expiringPointsArray[] = new ExpiringPoints(
            $expiringPoints['points'],
            $expiringPoints['expireAt']
        );
    }
}
