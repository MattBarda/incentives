<?php

namespace App\Incentive\Application\DTO;

use Carbon\Carbon;

class GetUserPoints
{
    private string $userId;
    private Carbon $pointsForDate;

    public function __construct(string $userId, Carbon $pointsForDate)
    {
        $this->userId = $userId;
        $this->pointsForDate = $pointsForDate;
    }

    public static function fromUICommandArray(array $data): self
    {
        return new self(
            $data['userId'],
            Carbon::create($data['pointsForDate'])
        );
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getPointsForDate(): Carbon
    {
        return $this->pointsForDate;
    }
}
