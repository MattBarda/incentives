<?php

namespace App\Incentive\Domain\User\Projector;

use App\Incentive\Domain\User\DomainEvent\BoosterApplied;
use Broadway\ReadModel\Projector;

class BoosterAppliedProjector extends Projector
{
    protected function applyBoosterApplied(BoosterApplied $event): void
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
            'points' => $userPoints,
            'expiringPoints' => array_merge(
                $oldExpiringPoints,
                [$event->expiringUserBonusPoints()->expirationDate()->toString()
                => $event->expiringUserBonusPoints()->userBonusPoints()->toInt()]),
        ];

        file_put_contents(
            __DIR__ . '/../../../../../public/user-' . $event->userId()->toString() . '.json',
            json_encode($user)
        );
    }
}
