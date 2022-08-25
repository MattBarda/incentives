<?php

namespace App\Incentive\Domain\User\CommandHandler;

use App\Incentive\Domain\User\Command\DeliveryComplete;
use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use App\Incentive\Infrastructure\Repository\EventStore\EventStoreUserRepository;
use Broadway\CommandHandling\SimpleCommandHandler;

class DeliveryCompleteHandler extends SimpleCommandHandler
{
    private EventStoreUserRepository $userRepository;
    // TODO this could come from flat DB table or wherever else
    // TODO another option would be to provide it in command - per user
    private int $deliveryBonusPoints;

    public function __construct(
        EventStoreUserRepository $userRepository,
        int $deliveryBonusPoints
    ) {
        $this->userRepository = $userRepository;
        $this->deliveryBonusPoints = $deliveryBonusPoints;
    }

    public function handleDeliveryComplete(DeliveryComplete $command): void
    {
        $user = $this->userRepository->load($command->userId()->toString());

        $user->completeDeliveryWithData(
            $command->deliveryId(),
            $command->completedAt(),
            ActionBonusPoints::fromInt($this->deliveryBonusPoints),
        );

        $this->userRepository->save($user);
    }
}
