<?php

namespace App\Incentive\Domain\User\ValueObject;

use App\Incentive\Domain\User\Exception\BoosterRangeException;
use Carbon\Carbon;
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
        if ($boosterActiveFrom->toCarbon()->greaterThan($boosterActiveTo->toCarbon())) {
            throw new BoosterRangeException(
                'Trying to activate booster where Booster activeFrom is before activeTo'
            );
        }
        if ($boosterActiveFrom->toCarbon()->lessThan(Carbon::now())) {
            throw new BoosterRangeException('Trying to Activate booster in the past.');
        }
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
