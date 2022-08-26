<?php

namespace App\Incentive\Domain\User\CommandHandler;

use App\Incentive\Domain\User\Command\RentStart;
use App\Incentive\Infrastructure\Repository\EventStore\EventStoreUserRepository;
use Broadway\CommandHandling\SimpleCommandHandler;

class RentStartHandler extends SimpleCommandHandler
{
    private EventStoreUserRepository $userRepository;

    public function __construct(
        EventStoreUserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function handleRentStart(RentStart $command): void
    {
        $user = $this->userRepository->load($command->userId()->toString());

        $user->rentStartWithData(
            $command->rentId(),
            $command->startedAt()
        );

        $this->userRepository->save($user);
    }
}
