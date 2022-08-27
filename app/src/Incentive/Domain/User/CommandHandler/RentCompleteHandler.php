<?php

namespace App\Incentive\Domain\User\CommandHandler;

use App\Incentive\Domain\User\Command\RentComplete;
use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use App\Incentive\Infrastructure\Repository\EventStore\EventStoreUserRepository;
use Broadway\CommandHandling\SimpleCommandHandler;

class RentCompleteHandler extends SimpleCommandHandler
{
    private EventStoreUserRepository $userRepository;
    private int $rentBonusPointsPerFullDay;

    public function __construct(
        EventStoreUserRepository $userRepository,
        int $rentBonusPointsPerFullDay
    ) {
        $this->userRepository = $userRepository;
        $this->rentBonusPointsPerFullDay = $rentBonusPointsPerFullDay;
    }

    public function handleRentComplete(RentComplete $command): void
    {
        $user = $this->userRepository->load($command->userId()->toString());

        $user->rentCompleteWithData(
            $command->rentId(),
            $command->completedAt(),
            ActionBonusPoints::fromInt($this->rentBonusPointsPerFullDay)
        );

        $this->userRepository->save($user);
    }
}
