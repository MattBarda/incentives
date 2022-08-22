<?php

namespace Incentive\Application\DTO;

use Carbon\Carbon;

class UserActionDTO
{
    private string $userId;
    private string $actionType;
    private string $finishedAt;

    private function __construct(
        string $userId,
        string $actionType,
        string $finishedAt
    ) {
        $this->userId = $userId;
        $this->actionType = $actionType;
        $this->finishedAt = $finishedAt;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['user_id']) ? (string)$data['user_id'] : '',
            isset($data['action_type']) ? (string)$data['action_type'] : '',
            isset($data['finished_at']) ? (string)$data['finished_at'] : '',
        );
    }

}
