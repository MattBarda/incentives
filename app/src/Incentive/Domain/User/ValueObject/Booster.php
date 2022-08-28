<?php

namespace App\Incentive\Domain\User\ValueObject;

class Booster
{
    private BoosterAppliedAt $appliedAt;
    private BoosterActiveRange $activeRange;
    private BoosterApplicableForAction $applicableForAction;
    private BoosterBonusPoints $boosterBonusPoints;
    private BoosterActionsRequired $boosterActionsRequired;

    private function __construct(
        BoosterAppliedAt $appliedAt,
        BoosterActiveRange $activeRange,
        BoosterApplicableForAction $applicableForAction,
        BoosterBonusPoints $boosterBonusPoints,
        BoosterActionsRequired $boosterActionsRequired
    ) {
        $this->appliedAt = $appliedAt;
        $this->activeRange = $activeRange;
        $this->applicableForAction = $applicableForAction;
        $this->boosterBonusPoints = $boosterBonusPoints;
        $this->boosterActionsRequired = $boosterActionsRequired;
    }

    public static function fromValueObjects(
        BoosterAppliedAt $appliedAt,
        BoosterActiveRange $activeRange,
        BoosterApplicableForAction $applicableForAction,
        BoosterBonusPoints $boosterBonusPoints,
        BoosterActionsRequired $boosterActionsRequired
    ): self {
        return new self(
            $appliedAt,
            $activeRange,
            $applicableForAction,
            $boosterBonusPoints,
            $boosterActionsRequired
        );
    }
}
