<?php

namespace App\Incentive\Domain\User\DomainEvent;

use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use App\Incentive\Domain\User\ValueObject\CompletedAt;
use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\UserId;
use Broadway\Serializer\Serializable;

class DeliveryCompleted implements Serializable
{
    private UserId $userId;
    private ActionId $deliveryId;
    private CompletedAt $completedAt;
    private ActionBonusPoints $actionBonusPoints;

    public function __construct(
        UserId            $userId,
        ActionId          $deliveryId,
        CompletedAt       $completedAt,
        ActionBonusPoints $actionBonusPoints
    ) {
        $this->userId = $userId;
        $this->deliveryId = $deliveryId;
        $this->completedAt = $completedAt;
        $this->actionBonusPoints = $actionBonusPoints;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function deliveryId(): ActionId
    {
        return $this->deliveryId;
    }

    public function completedAt(): CompletedAt
    {
        return $this->completedAt;
    }

    public function actionBonusPoints(): ActionBonusPoints
    {
        return $this->actionBonusPoints;
    }

    public static function deserialize(array $data): self
    {
        return new self(
            UserId::fromString($data['userId']),
            ActionId::fromString($data['deliveryId']),
            CompletedAt::fromString($data['completedAt']),
            ActionBonusPoints::fromInt($data['actionBonusPoints'])
        );
    }

    public function serialize(): array
    {
        return [
            'userId' => $this->userId->toString(),
            'deliveryId' => $this->deliveryId->toString(),
            'completedAt' => $this->completedAt->toString(),
            'actionBonusPoints' => $this->actionBonusPoints()->toInt()
        ];
    }
}
