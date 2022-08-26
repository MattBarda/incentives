<?php

namespace App\Incentive\Domain\User\DomainEvent;

use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\StartedAt;
use App\Incentive\Domain\User\ValueObject\UserId;
use Broadway\Serializer\Serializable;

class RentStarted implements Serializable
{
    private UserId $userId;
    private ActionId $rentId;
    private StartedAt $startedAt;

    public function __construct(
        UserId $userId,
        ActionId $rentId,
        StartedAt $startedAt
    ) {
        $this->userId = $userId;
        $this->rentId = $rentId;
        $this->startedAt = $startedAt;
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

    public static function deserialize(array $data): self
    {
        return new self(
            UserId::fromString($data['userId']),
            ActionId::fromString($data['rentId']),
            StartedAt::fromString($data['startedAt'])
        );
    }

    public function serialize(): array
    {
        return [
            'userId' => $this->userId->toString(),
            'rentId' => $this->rentId->toString(),
            'startedAt' => $this->startedAt->toString()
        ];
    }
}
