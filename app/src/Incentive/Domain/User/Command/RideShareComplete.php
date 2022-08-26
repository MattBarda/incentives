<?php

namespace App\Incentive\Domain\User\Command;

use App\Incentive\Domain\User\ValueObject\CompletedAt;
use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\UserId;

class RideShareComplete
{
    private UserId $userId;
    private ActionId $rideShareId;
    private CompletedAt $completedAt;

    public function __construct(
        string $userId,
        string $rideShareId,
        string $completedAt
    ) {
        $this->userId = UserId::fromString($userId);
        $this->rideShareId = ActionId::fromString($rideShareId);
        $this->completedAt = CompletedAt::fromString($completedAt);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['userId'],
            $data['rideShareId'],
            $data['completedAt']
        );
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function rideShareId(): ActionId
    {
        return $this->rideShareId;
    }

    public function completedAt(): CompletedAt
    {
        return $this->completedAt;
    }
}
