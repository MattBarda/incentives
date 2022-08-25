<?php

namespace App\Incentive\Domain\User\CommandHandler;

use App\Incentive\Domain\User\Command\RegisterUser;
use App\Incentive\Domain\User\User;
use App\Incentive\Infrastructure\Repository\EventStore\EventStoreUserRepository;
use Broadway\CommandHandling\SimpleCommandHandler;

class RegisterUserHandler extends SimpleCommandHandler
{
    private EventStoreUserRepository $userRepository;

    public function __construct(EventStoreUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handleRegisterUser(RegisterUser $command): void
    {
        $user = User::registerWithData(
            $command->userId(),
            $command->userName(),
            $command->emailAddress()
        );

        $this->userRepository->save($user);
    }
}
