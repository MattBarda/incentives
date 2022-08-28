<?php

namespace App\Incentive\Domain\User\ValueObject;

class ExpiringUserBonusPoints
{
    private UserBonusPoints $userBonusPoints;
    private ExpirationDate $expirationDate;

    private function __construct(
        UserBonusPoints $userBonusPoints,
        ExpirationDate $expirationDate
    ) {

        $this->userBonusPoints = $userBonusPoints;
        $this->expirationDate = $expirationDate;
    }

    public static function fromUserBonusPointsAndExpirationDate(
        UserBonusPoints $userBonusPoints,
        ExpirationDate $expirationDate
    ): self {
        return new self(
            $userBonusPoints,
            $expirationDate
        );
    }

    public function userBonusPoints(): UserBonusPoints
    {
        return $this->userBonusPoints;
    }

    public function expirationDate(): ExpirationDate
    {
        return $this->expirationDate;
    }
}
