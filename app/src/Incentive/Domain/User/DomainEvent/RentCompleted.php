<?php

namespace App\Incentive\Domain\User\DomainEvent;

use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\CompletedAt;
use App\Incentive\Domain\User\ValueObject\UserId;
use Broadway\Serializer\Serializable;

class RentCompleted implements Serializable
{
    private UserId $userId;
    private ActionId $rentId;
    private CompletedAt $completedAt;
    private ActionBonusPoints $actionBonusPointsPerDay;

    public function __construct(
        UserId $userId,
        ActionId $rentId,
        CompletedAt $completedAt,
        ActionBonusPoints $actionBonusPointsPerDay
    ) {

        $this->userId = $userId;
        $this->rentId = $rentId;
        $this->completedAt = $completedAt;
        $this->actionBonusPointsPerDay = $actionBonusPointsPerDay;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function rentId(): ActionId
    {
        return $this->rentId;
    }

    public function completedAt(): CompletedAt
    {
        return $this->completedAt;
    }

    public function actionBonusPoints(): ActionBonusPoints
    {
        return $this->actionBonusPointsPerDay;
    }

    public static function deserialize(array $data): self
    {
        return new self(
            UserId::fromString($data['userId']),
            ActionId::fromString($data['rentId']),
            CompletedAt::fromString($data['completedAt']),
            ActionBonusPoints::fromInt($data['actionBonusPoints'])
        );
    }

    public function serialize(): array
    {
        return [
            'userId' => $this->userId->toString(),
            'rentId' => $this->rentId->toString(),
            'completedAt' => $this->completedAt->toString(),
            'actionBonusPoints' => $this->actionBonusPoints()->toInt()
        ];
    }
}
