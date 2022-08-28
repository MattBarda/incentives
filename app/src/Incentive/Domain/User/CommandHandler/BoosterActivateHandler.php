<?php

namespace App\Incentive\Domain\User\CommandHandler;

use App\Incentive\Domain\User\Command\BoosterActivate;
use App\Incentive\Infrastructure\Repository\EventStore\EventStoreUserRepository;
use Broadway\CommandHandling\SimpleCommandHandler;

class BoosterActivateHandler extends SimpleCommandHandler
{
    private EventStoreUserRepository $userRepository;

    public function __construct(
        EventStoreUserRepository $userRepository,
    ) {
        $this->userRepository = $userRepository;
    }

    public function handleBoosterActivate(BoosterActivate $command): void
    {
        $user = $this->userRepository->load($command->userId()->toString());

        $user->boosterActivateWithData(
            $command->userId(),
            $command->boosterId(),
            $command->appliedAt(),
            $command->activeRange(),
            $command->applicableForAction(),
            $command->boosterBonusPoints(),
            $command->getBoosterActionsRequired()
        );

        $this->userRepository->save($user);
    }
}
