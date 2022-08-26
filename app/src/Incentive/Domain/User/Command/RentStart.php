<?php

namespace App\Incentive\Domain\User\Command;

use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\StartedAt;
use App\Incentive\Domain\User\ValueObject\UserId;

class RentStart
{
    private UserId $userId;
    private ActionId $rentId;
    private StartedAt $startedAt;

    public function __construct(
        string $userId,
        string $rentId,
        string $startedAt
    ) {
        $this->userId = UserId::fromString($userId);
        $this->rentId = ActionId::fromString($rentId);
        $this->startedAt = StartedAt::fromString($startedAt);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['userId'],
            $data['rentId'],
            $data['startedAt']
        );
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function rentId(): ActionId
    {
        return $this->rentId;
    }

    public function startedAt(): StartedAt
    {
        return $this->startedAt;
    }
}
