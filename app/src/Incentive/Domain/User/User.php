<?php

namespace App\Incentive\Domain\User;

use App\Incentive\Domain\User\DomainEvent\DeliveryCompleted;
use App\Incentive\Domain\User\DomainEvent\RideShareCompleted;
use App\Incentive\Domain\User\DomainEvent\UserWasRegistered;
use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use App\Incentive\Domain\User\ValueObject\CompletedAt;
use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\EmailAddress;
use App\Incentive\Domain\User\ValueObject\UserBonusPoints;
use App\Incentive\Domain\User\ValueObject\UserId;
use App\Incentive\Domain\User\ValueObject\UserName;
use Broadway\EventSourcing\EventSourcedAggregateRoot;

class User extends EventSourcedAggregateRoot
{
    private UserId $userId;
    private UserName $userName;
    private EmailAddress $emailAddress;
    private UserBonusPoints $userBonusPoints;
    private array $completedDeliveries = [];
    private array $completedRideShares = [];

    public function getAggregateRootId(): string
    {
        return $this->userId->toString();
    }

    public static function registerWithData(
        UserId $userId,
        UserName $name,
        EmailAddress $emailAddress
    ): self {
        $user = new self();
        $user->register($userId, $name, $emailAddress);

        return $user;
    }

    private function register(
        UserId $userId,
        UserName $name,
        EmailAddress $emailAddress
    ): void {
        $this->apply(
            new UserWasRegistered($userId, $name, $emailAddress)
        );
    }

    protected function applyUserWasRegistered(UserWasRegistered $event): void
    {
        $this->userId = $event->userId();
        $this->userName = $event->userName();
        $this->emailAddress = $event->emailAddress();
        $this->userBonusPoints = UserBonusPoints::init();
    }

    public function completeDeliveryWithData(
        ActionId          $deliveryId,
        CompletedAt       $completedAt,
        ActionBonusPoints $actionBonusPoints
    ): void {
        $this->apply(
            new DeliveryCompleted(
                $this->userId,
                $deliveryId,
                $completedAt,
                $actionBonusPoints
            )
        );
    }

    protected function applyDeliveryCompleted(DeliveryCompleted $event): void
    {
        $this->completedDeliveries[$event->deliveryId()->toString()] = $event->completedAt();

        $this->userBonusPoints = UserBonusPoints::addActionBonusPoints(
            $this->userBonusPoints, $event->actionBonusPoints()
        );
    }

    public function completeRideShareWithData(
        ActionId          $deliveryId,
        CompletedAt       $completedAt,
        ActionBonusPoints $actionBonusPoints
    ): void {
        $this->apply(
            new RideShareCompleted(
                $this->userId,
                $deliveryId,
                $completedAt,
                $actionBonusPoints
            )
        );
    }

    protected function applyRideShareCompleted(RideShareCompleted $event): void
    {
        $this->completedRideShares[$event->rideShareId()->toString()] = $event->completedAt();

        $this->userBonusPoints = UserBonusPoints::addActionBonusPoints(
            $this->userBonusPoints, $event->actionBonusPoints()
        );
    }
}
