<?php

namespace App\Incentive\Domain\User\Command;

use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\CompletedAt;
use App\Incentive\Domain\User\ValueObject\UserId;

class RentComplete
{
    private UserId $userId;
    private ActionId $rentId;
    private CompletedAt $completedAt;

    public function __construct(
        string $userId,
        string $rentId,
        string $completedAt
    ) {
        $this->userId = UserId::fromString($userId);
        $this->rentId = ActionId::fromString($rentId);
        $this->completedAt = CompletedAt::fromString($completedAt);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['userId'],
            $data['rentId'],
            $data['completedAt']
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

    public function completedAt(): CompletedAt
    {
        return $this->completedAt;
    }
}
