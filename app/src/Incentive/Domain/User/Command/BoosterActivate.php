<?php

namespace App\Incentive\Domain\User\Command;

use App\Incentive\Domain\User\ValueObject\BoosterActionsRequired;
use App\Incentive\Domain\User\ValueObject\BoosterApplicableForAction;
use App\Incentive\Domain\User\ValueObject\BoosterAppliedAt;
use App\Incentive\Domain\User\ValueObject\BoosterBonusPoints;
use App\Incentive\Domain\User\ValueObject\BoosterBonusPointsValidFor;
use App\Incentive\Domain\User\ValueObject\BoosterId;
use App\Incentive\Domain\User\ValueObject\BoosterValidFrom;
use App\Incentive\Domain\User\ValueObject\BoosterValidTo;
use App\Incentive\Domain\User\ValueObject\UserId;

class BoosterActivate
{
    private UserId $userId;
    private BoosterId $boosterId;
    private BoosterAppliedAt $appliedAt;
    private BoosterValidFrom $validFrom;
    private BoosterValidTo $validTo;
    private BoosterApplicableForAction $applicableForAction;
    private BoosterBonusPoints $boosterBonusPoints;
    private BoosterActionsRequired $boosterActionsRequired;

    public function __construct(
        string $userId,
        string $boosterId,
        string $appliedAt,
        string $validFrom,
        string $validTo,
        string $applicableForAction,
        int $boosterBonusPoints,
        string $boosterBonusPointsValidFor,
        int $boosterActionsRequired
    ) {
        $this->userId = UserId::fromString($userId);
        $this->boosterId = BoosterId::fromString($boosterId);
        $this->appliedAt = BoosterAppliedAt::fromString($appliedAt);
        $this->validFrom = BoosterValidFrom::fromString($validFrom);
        $this->validTo = BoosterValidTo::fromString($validTo);
        $this->applicableForAction = BoosterApplicableForAction::fromString($applicableForAction);
        $this->boosterBonusPoints = BoosterBonusPoints::fromIntAndExpireAt(
            $boosterBonusPoints,
            BoosterBonusPointsValidFor::fromDaysAsInt($boosterBonusPointsValidFor)
        );
        $this->boosterActionsRequired = BoosterActionsRequired::fromInt($boosterActionsRequired);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['userId'],
            $data['boosterId'],
            $data['appliedAt'],
            $data['validFrom'],
            $data['validTo'],
            $data['applicableForAction'],
            $data['boosterBonusPoints'],
            $data['boosterBonusPointsValidFor'],
            $data['boosterActionsRequired'],
        );
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

    public function validFrom(): BoosterValidFrom
    {
        return $this->validFrom;
    }

    public function validTo(): BoosterValidTo
    {
        return $this->validTo;
    }

    public function applicableForAction(): BoosterApplicableForAction
    {
        return $this->applicableForAction;
    }

    public function boosterBonusPoints(): BoosterBonusPoints
    {
        return $this->boosterBonusPoints;
    }

    public function getBoosterActionsRequired(): BoosterActionsRequired
    {
        return $this->boosterActionsRequired;
    }
}
