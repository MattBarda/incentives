<?php

namespace App\Incentive\Domain\User\Command;

use App\Incentive\Domain\User\ValueObject\CompletedAt;
use App\Incentive\Domain\User\ValueObject\DeliveryId;
use App\Incentive\Domain\User\ValueObject\UserId;

class DeliveryComplete
{
    private UserId $userId;
    private DeliveryId $deliveryId;
    private CompletedAt $completedAt;

    public function __construct(
        string $userId,
        string $deliveryId,
        string $completedAt
    ) {
        $this->userId = UserId::fromString($userId);
        $this->deliveryId = DeliveryId::fromString($deliveryId);
        $this->completedAt = CompletedAt::fromString($completedAt);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['userId'],
            $data['deliveryId'],
            $data['completedAt']
        );
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function deliveryId(): DeliveryId
    {
        return $this->deliveryId;
    }

    public function completedAt(): CompletedAt
    {
        return $this->completedAt;
    }
}
