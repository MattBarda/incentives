<?php

namespace App\Incentive\Domain\User\ValueObject;

class Booster
{
    private BoosterAppliedAt $appliedAt;
    private BoosterValidFrom $validFrom;
    private BoosterValidTo $validTo;
    private BoosterApplicableForAction $applicableForAction;
    private BoosterBonusPoints $boosterBonusPoints;
    private BoosterActionsRequired $boosterActionsRequired;

    private function __construct(
        BoosterAppliedAt $appliedAt,
        BoosterValidFrom $validFrom,
        BoosterValidTo $validTo,
        BoosterApplicableForAction $applicableForAction,
        BoosterBonusPoints $boosterBonusPoints,
        BoosterActionsRequired $boosterActionsRequired
    ) {
        $this->appliedAt = $appliedAt;
        $this->validFrom = $validFrom;
        $this->validTo = $validTo;
        $this->applicableForAction = $applicableForAction;
        $this->boosterBonusPoints = $boosterBonusPoints;
        $this->boosterActionsRequired = $boosterActionsRequired;
    }

    public static function fromValueObjects(
        BoosterAppliedAt $appliedAt,
        BoosterValidFrom $validFrom,
        BoosterValidTo $validTo,
        BoosterApplicableForAction $applicableForAction,
        BoosterBonusPoints $boosterBonusPoints,
        BoosterActionsRequired $boosterActionsRequired
    ): self {
        return new self(
            $appliedAt,
            $validFrom,
            $validTo,
            $applicableForAction,
            $boosterBonusPoints,
            $boosterActionsRequired
        );
    }
}
