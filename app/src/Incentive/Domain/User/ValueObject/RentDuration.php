<?php

namespace App\Incentive\Domain\User\ValueObject;

use DateInterval;

class RentDuration
{
    private DateInterval $interval;

    private function __construct(StartedAt $startedAt, CompletedAt $completedAt)
    {
        $this->interval = $startedAt->toCarbon()->diff($completedAt->toCarbon());
    }

    public static function fromStartedAtAndCompletedAt(StartedAt $startedAt, CompletedAt $completedAt): self
    {
        return new self($startedAt, $completedAt);
    }

    public function fullDays(): int
    {
        return  $this->interval->days;
    }
}
