<?php

namespace App\Incentive\Domain\User\DomainEvent;

use App\Incentive\Domain\User\ValueObject\BoosterApplicableForAction;
use App\Incentive\Domain\User\ValueObject\ExpirationDate;
use App\Incentive\Domain\User\ValueObject\ExpiringUserBonusPoints;
use App\Incentive\Domain\User\ValueObject\UserBonusPoints;
use App\Incentive\Domain\User\ValueObject\UserId;
use Broadway\Serializer\Serializable;

class BoosterApplied implements Serializable
{
    private UserId $userId;
    private ExpiringUserBonusPoints $expiringUserBonusPoints;
    private BoosterApplicableForAction $actionType;

    public function __construct(
        UserId $userId,
        ExpiringUserBonusPoints $expiringUserBonusPoints,
        BoosterApplicableForAction $actionType
    ) {
        $this->userId = $userId;
        $this->expiringUserBonusPoints = $expiringUserBonusPoints;
        $this->actionType = $actionType;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function expiringUserBonusPoints(): ExpiringUserBonusPoints
    {
        return $this->expiringUserBonusPoints;
    }

    public function action(): BoosterApplicableForAction
    {
        return $this->actionType;
    }

    public static function deserialize(array $data): self
    {
        return new self(
            UserId::fromString($data['userId']),
            ExpiringUserBonusPoints::fromUserBonusPointsAndExpirationDate(
                UserBonusPoints::fromInt($data['userBonusPoints']),
                ExpirationDate::fromString($data['expirationDate']),
            ),
            BoosterApplicableForAction::fromString($data['actionType'])
        );
    }

    public function serialize(): array
    {
        return [
            'userId' => $this->userId->toString(),
            'userBonusPoints' => $this->expiringUserBonusPoints->userBonusPoints()->toInt(),
            'expirationDate' => $this->expiringUserBonusPoints->expirationDate()->toString(),
            'actionType' => $this->actionType->toString(),
        ];
    }
}
