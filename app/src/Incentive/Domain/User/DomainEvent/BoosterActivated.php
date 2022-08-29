<?php

namespace App\Incentive\Domain\User\DomainEvent;

use App\Incentive\Domain\User\ValueObject\BoosterActionsRequired;
use App\Incentive\Domain\User\ValueObject\BoosterActiveRange;
use App\Incentive\Domain\User\ValueObject\BoosterApplicableForAction;
use App\Incentive\Domain\User\ValueObject\BoosterAppliedAt;
use App\Incentive\Domain\User\ValueObject\BoosterBonusPoints;
use App\Incentive\Domain\User\ValueObject\BoosterBonusPointsValidFor;
use App\Incentive\Domain\User\ValueObject\BoosterId;
use App\Incentive\Domain\User\ValueObject\BoosterActiveFrom;
use App\Incentive\Domain\User\ValueObject\BoosterActiveTo;
use App\Incentive\Domain\User\ValueObject\UserId;
use Broadway\Serializer\Serializable;

class BoosterActivated implements Serializable
{
    private UserId $userId;
    private BoosterId $boosterId;
    private BoosterAppliedAt $appliedAt;
    private BoosterActiveRange $activeRange;
    private BoosterApplicableForAction $applicableForAction;
    private BoosterBonusPoints $boosterBonusPoints;
    private BoosterActionsRequired $boosterActionsRequired;

    public function __construct(
        UserId $userId,
        BoosterId $boosterId,
        BoosterAppliedAt $appliedAt,
        BoosterActiveRange $activeRange,
        BoosterApplicableForAction $applicableForAction,
        BoosterBonusPoints $boosterBonusPoints,
        BoosterActionsRequired $boosterActionsRequired
    ) {
        $this->userId = $userId;
        $this->boosterId = $boosterId;
        $this->appliedAt = $appliedAt;
        $this->activeRange = $activeRange;
        $this->applicableForAction = $applicableForAction;
        $this->boosterBonusPoints = $boosterBonusPoints;
        $this->boosterActionsRequired = $boosterActionsRequired;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function boosterId(): BoosterId
    {
        return $this->boosterId;
    }

    public function appliedAt(): BoosterAppliedAt
    {
        return $this->appliedAt;
    }

    public function activeRange(): BoosterActiveRange
    {
        return $this->activeRange;
    }

    public function applicableForAction(): BoosterApplicableForAction
    {
        return $this->applicableForAction;
    }

    public function boosterBonusPoints(): BoosterBonusPoints
    {
        return $this->boosterBonusPoints;
    }

    public function boosterActionsRequired(): BoosterActionsRequired
    {
        return $this->boosterActionsRequired;
    }

    public static function deserialize(array $data): self
    {
        return new self(
            UserId::fromString($data['userId']),
            BoosterId::fromString($data['boosterId']),
            BoosterAppliedAt::fromString($data['appliedAt']),
            BoosterActiveRange::fromActiveFromAndActiveTo(
                BoosterActiveFrom::fromString($data['activeFrom']),
                BoosterActiveTo::fromString($data['activeTo'])
            ),
            BoosterApplicableForAction::fromString($data['applicableForAction']),
            BoosterBonusPoints::fromIntAndExpireAt(
                $data['boosterBonusPoints'],
                BoosterBonusPointsValidFor::fromDaysAsInt($data['boosterBonusPointsValidForDays'])
            ),
            BoosterActionsRequired::fromInt($data['boosterActionsRequired'])
        );
    }

    public function serialize(): array
    {
        return [
            'userId' => $this->userId->toString(),
            'boosterId' => $this->boosterId->toString(),
            'appliedAt' => $this->appliedAt->toString(),
            'activeFrom' => $this->activeRange->boosterActiveFrom()->toString(),
            'activeTo' => $this->activeRange->boosterActiveTo()->toString(),
            'applicableForAction' => $this->applicableForAction->toString(),
            'boosterBonusPoints' => $this->boosterBonusPoints->pointsAsInt(),
            'boosterBonusPointsValidForDays' => $this->boosterBonusPoints->boosterBonusPointsValidFor()->toInt(),
            'boosterActionsRequired' => $this->boosterActionsRequired->toInt()
        ];
    }
}
