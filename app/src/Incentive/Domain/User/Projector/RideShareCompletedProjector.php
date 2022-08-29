<?php

namespace App\Incentive\Domain\User\Projector;

use App\Incentive\Domain\User\DomainEvent\RideShareCompleted;
use Broadway\ReadModel\Projector;

class RideShareCompletedProjector extends Projector
{
    protected function applyRideShareCompleted(RideShareCompleted $event): void
    {
        $userExist = file_exists(
            __DIR__ . '/../../../../../public/user-' . $event->userId()->toString() . '.json'
        );

        if ($userExist) {
            $user = json_decode(file_get_contents(
                __DIR__ . '/../../../../../public/user-' . $event->userId()->toString() . '.json'
            ), true);
            $userPoints = $user[$event->userId()->toString()]['points'];
            $userExpiringPoints = $user[$event->userId()->toString()]['expiringPoints'];
        } else {
            $userPoints = 0;
            $userExpiringPoints = [];
        }

        $user[$event->userId()->toString()] = [
            'userId' => $event->userId()->toString(),
            'points' => $userPoints + $event->actionBonusPoints()->toInt(),
            'expiringPoints' => $userExpiringPoints
        ];

        file_put_contents(
            __DIR__ . '/../../../../../public/user-' . $event->userId()->toString() . '.json',
            json_encode($user)
        );
    }
}
