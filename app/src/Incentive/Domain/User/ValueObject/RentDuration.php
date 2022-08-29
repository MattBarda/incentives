<?php

namespace App\Incentive\Domain\User\ValueObject;

use DateInterval;

class RentDuration
{
    private DateInterval $interval;
    private StartedAt $startedAt;
    private CompletedAt $completedAt;

    private function __construct(StartedAt $startedAt, CompletedAt $completedAt)
    {
        $this->interval = $startedAt->toCarbon()->diff($completedAt->toCarbon());
        $this->startedAt = $startedAt;
        $this->completedAt = $completedAt;
    }

    public static function fromStartedAtAndCompletedAt(StartedAt $startedAt, CompletedAt $completedAt): self
    {
        return new self($startedAt, $completedAt);
    }

    public function fullDays(): int
    {
        return  $this->interval->days;
    }

    /**
     * @return StartedAt
     */
    public function startedAt(): StartedAt
    {
        return $this->startedAt;
    }

    /**
     * @return CompletedAt
     */
    public function completedAt(): CompletedAt
    {
        return $this->completedAt;
    }
}
