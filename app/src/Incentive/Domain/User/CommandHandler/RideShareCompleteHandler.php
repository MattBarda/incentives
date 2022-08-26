<?php

namespace App\Incentive\Domain\User\CommandHandler;

use App\Incentive\Domain\User\Command\RideShareComplete;
use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use App\Incentive\Infrastructure\Repository\EventStore\EventStoreUserRepository;
use Broadway\CommandHandling\SimpleCommandHandler;

class RideShareCompleteHandler extends SimpleCommandHandler
{
    private EventStoreUserRepository $userRepository;
    // TODO this could come from flat DB table or wherever else
    // TODO another option would be to provide it in command - per user
    private int $rideShareBonusPoints;

    public function __construct(
        EventStoreUserRepository $userRepository,
        int $rideShareBonusPoints
    ) {
        $this->userRepository = $userRepository;
        $this->rideShareBonusPoints = $rideShareBonusPoints;
    }

    public function handleRideShareComplete(RideShareComplete $command): void
    {
        $user = $this->userRepository->load($command->userId()->toString());

        $user->completeRideShareWithData(
            $command->rideShareId(),
            $command->completedAt(),
            ActionBonusPoints::fromInt($this->rideShareBonusPoints),
        );

        $this->userRepository->save($user);
    }
}
