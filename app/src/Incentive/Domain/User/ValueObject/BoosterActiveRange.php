<?php

namespace App\Incentive\Domain\User\ValueObject;

use App\Incentive\Domain\User\Exception\BoosterRangeException;
use DateInterval;

class BoosterActiveRange
{
    private BoosterActiveFrom $boosterActiveFrom;
    private BoosterActiveTo $boosterActiveTo;
    private DateInterval $boosterDuration;

    private function __construct(
        BoosterActiveFrom $boosterActiveFrom,
        BoosterActiveTo $boosterActiveTo
    ) {
        if ($boosterActiveFrom->toCarbon()->isAfter($boosterActiveTo->toCarbon())) {
            throw new BoosterRangeException(
                'Booster activeFrom is after activeTo'
            );
        }
        //TODO figure out where to put this check as it breaks when reconstituteFromEventStream happens,
        // unless we want to activate boosters applicable for past actions
//        if ($boosterActiveFrom->toCarbon()->isBefore(Carbon::now())) {
//            throw new BoosterRangeException('Booster can not start in the past');
//        }
        $this->boosterActiveFrom = $boosterActiveFrom;
        $this->boosterActiveTo = $boosterActiveTo;
        $this->boosterDuration = $boosterActiveFrom->toCarbon()->diff($boosterActiveTo->toCarbon());

    }

    public static function fromActiveFromAndActiveTo(
        BoosterActiveFrom $boosterActiveFrom,
        BoosterActiveTo $boosterActiveTo
    ): self {
        return new self($boosterActiveFrom, $boosterActiveTo);
    }

    /**
     * @return BoosterActiveFrom
     */
    public function boosterActiveFrom(): BoosterActiveFrom
    {
        return $this->boosterActiveFrom;
    }

    /**
     * @return BoosterActiveTo
     */
    public function boosterActiveTo(): BoosterActiveTo
    {
        return $this->boosterActiveTo;
    }

    /**
     * @return DateInterval
     */
    public function boosterDurationASDateInterval(): DateInterval
    {
        return $this->boosterDuration;
    }
}
