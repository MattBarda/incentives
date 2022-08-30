<?php

namespace App\Incentive\Domain\User\Repository\ReadModel;

class ExpiringPoints
{
    private int $points;
    private string $expireAt;

    public function __construct(int $points, string $expireAt)
    {
        $this->points = $points;
        $this->expireAt = $expireAt;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function getExpireAt(): string
    {
        return $this->expireAt;
    }

    public function toArray(): array
    {
        return [
            'points' => $this->points,
            'expireAt' => $this->expireAt
        ];
    }
}
