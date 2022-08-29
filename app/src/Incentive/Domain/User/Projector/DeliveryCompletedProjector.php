<?php

namespace App\Incentive\Domain\User\Projector;

use App\Incentive\Domain\User\DomainEvent\DeliveryCompleted;
use Broadway\ReadModel\Projector;

class DeliveryCompletedProjector extends Projector
{
    protected function applyDeliveryCompleted(DeliveryCompleted $event): void
    {
        $userExist = file_exists(
            __DIR__ . '/../../../../../public/user-' . $event->userId()->toString() . '.json'
        );

        if ($userExist) {
            $user = json_decode(file_get_contents(
                __DIR__ . '/../../../../../public/user-' . $event->userId()->toString() . '.json'
            ), true);
            $userPoints = $user[$event->userId()->toString()]['points'];
            $oldExpiringPoints = $user[$event->userId()->toString()]['expiringPoints'];
        } else {
            $userPoints = 0;
            $oldExpiringPoints = [];
        }

        $user[$event->userId()->toString()] = [
            'userId' => $event->userId()->toString(),
            'points' => $userPoints + $event->actionBonusPoints()->toInt(),
            'expiringPoints' => $oldExpiringPoints
        ];

        file_put_contents(
            __DIR__ . '/../../../../../public/user-' . $event->userId()->toString() . '.json',
            json_encode($user)
        );
    }
}
