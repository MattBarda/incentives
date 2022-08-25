<?php

namespace App\Incentive\Domain\User\EventStore;


use App\Incentive\Domain\User\User;
use App\Incentive\Domain\User\ValueObject\UserId;

interface UserStore
{
    public function save(User $user): void;

    public function get(UserId $userId): ?User;
}
