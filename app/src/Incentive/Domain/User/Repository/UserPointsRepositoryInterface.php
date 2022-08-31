<?php

namespace App\Incentive\Domain\User\Repository;

use App\Incentive\Domain\User\Repository\ReadModel\UserPoints;
use Carbon\Carbon;

interface UserPointsRepositoryInterface
{
    public function save(UserPoints $userPoints): void;
    public function getPointsForDate(string $userId, Carbon $date): int;
    public function init(string $userId): void;
}
