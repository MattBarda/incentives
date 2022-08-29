<?php

namespace App\Incentive\Domain\User\DomainEvent;

use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\CompletedAt;
use App\Incentive\Domain\User\ValueObject\RentDuration;
use App\Incentive\Domain\User\ValueObject\StartedAt;
use App\Incentive\Domain\User\ValueObject\UserBonusPoints;
use App\Incentive\Domain\User\ValueObject\UserId;
use Broadway\Serializer\Serializable;

class RentCompleted implements Serializable
{
    private UserId $userId;
    private ActionId $rentId;
    private ActionBonusPoints $actionBonusPointsPerDay;
    private RentDuration $rentDuration;
    private ActionBonusPoints $actionBonusPoints;

    public function __construct(
        UserId $userId,
        ActionId $rentId,
        ActionBonusPoints $actionBonusPointsPerDay,
        RentDuration $rentDuration,
        ActionBonusPoints $actionBonusPoints
    ) {

        $this->userId = $userId;
        $this->rentId = $rentId;
        $this->actionBonusPointsPerDay = $actionBonusPointsPerDay;
        $this->rentDuration = $rentDuration;
        $this->actionBonusPoints = $actionBonusPoints;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function rentId(): ActionId
    {
        return $this->rentId;
    }

    public function actionBonusPointsPerDay(): ActionBonusPoints
    {
        return $this->actionBonusPointsPerDay;
    }

    public function rentDuration(): RentDuration
    {
        return $this->rentDuration;
    }

    public function actionBonusPoints(): ActionBonusPoints
    {
        return $this->actionBonusPoints;
    }

    public static function deserialize(array $data): self
    {
        return new self(
            UserId::fromString($data['userId']),
            ActionId::fromString($data['rentId']),
            ActionBonusPoints::fromInt($data['actionBonusPointsPerDay']),
            RentDuration::fromStartedAtAndCompletedAt(
                StartedAt::fromString($data['startedAt']),
                CompletedAt::fromString($data['completedAt'])
            ),
            ActionBonusPoints::fromInt($data['actionBonusPoints'])
        );
    }

    public function serialize(): array
    {
        return [
            'userId' => $this->userId->toString(),
            'rentId' => $this->rentId->toString(),
            'actionBonusPointsPerDay' => $this->actionBonusPointsPerDay->toInt(),
            'startedAt' => $this->rentDuration->startedAt()->toString(),
            'completedAt' => $this->rentDuration->completedAt()->toString(),
            'actionBonusPoints' => $this->actionBonusPoints->toInt(),
            'fullDays' => $this->rentDuration->fullDays()
        ];
    }
}
