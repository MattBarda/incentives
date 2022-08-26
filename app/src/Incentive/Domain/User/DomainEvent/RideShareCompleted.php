<?php

namespace App\Incentive\Domain\User\DomainEvent;

use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\CompletedAt;
use App\Incentive\Domain\User\ValueObject\UserId;
use Broadway\Serializer\Serializable;

class RideShareCompleted implements Serializable
{
    private UserId $userId;
    private ActionId $rideShareId;
    private CompletedAt $completedAt;
    private ActionBonusPoints $actionBonusPoints;

    public function __construct(
        UserId            $userId,
        ActionId          $rideShareId,
        CompletedAt       $completedAt,
        ActionBonusPoints $actionBonusPoints
    ) {
        $this->userId = $userId;
        $this->rideShareId = $rideShareId;
        $this->completedAt = $completedAt;
        $this->actionBonusPoints = $actionBonusPoints;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function rideShareId(): ActionId
    {
        return $this->rideShareId;
    }

    public function completedAt(): CompletedAt
    {
        return $this->completedAt;
    }

    public function actionBonusPoints(): ActionBonusPoints
    {
        return $this->actionBonusPoints;
    }

    public static function deserialize(array $data)
    {
        return new self(
            UserId::fromString($data['userId']),
            ActionId::fromString($data['rideShareId']),
            CompletedAt::fromString($data['completedAt']),
            ActionBonusPoints::fromInt($data['actionBonusPoints'])
        );
    }

    public function serialize(): array
    {
        return [
            'userId' => $this->userId->toString(),
            'rideShareId' => $this->rideShareId->toString(),
            'completedAt' => $this->completedAt->toString(),
            'actionBonusPoints' => $this->actionBonusPoints()->toInt()
        ];
    }
}
