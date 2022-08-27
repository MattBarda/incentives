<?php

namespace App\Incentive\Domain\User;

use App\Incentive\Domain\User\DomainEvent\BoosterActivated;
use App\Incentive\Domain\User\DomainEvent\DeliveryCompleted;
use App\Incentive\Domain\User\DomainEvent\RentCompleted;
use App\Incentive\Domain\User\DomainEvent\RentStarted;
use App\Incentive\Domain\User\DomainEvent\RideShareCompleted;
use App\Incentive\Domain\User\DomainEvent\UserWasRegistered;
use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use App\Incentive\Domain\User\ValueObject\Booster;
use App\Incentive\Domain\User\ValueObject\BoosterActionsRequired;
use App\Incentive\Domain\User\ValueObject\BoosterApplicableForAction;
use App\Incentive\Domain\User\ValueObject\BoosterAppliedAt;
use App\Incentive\Domain\User\ValueObject\BoosterBonusPoints;
use App\Incentive\Domain\User\ValueObject\BoosterId;
use App\Incentive\Domain\User\ValueObject\BoosterValidFrom;
use App\Incentive\Domain\User\ValueObject\BoosterValidTo;
use App\Incentive\Domain\User\ValueObject\CompletedAt;
use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\EmailAddress;
use App\Incentive\Domain\User\ValueObject\RentDuration;
use App\Incentive\Domain\User\ValueObject\StartedAt;
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
    private array $rentsStarted = [];
    private array $rentsCompleted = [];
    private array $activeBoosters = [];

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

    public function rentStartWithData(
        ActionId $rentId,
        StartedAt $startedAd
    ): void {
        $this->apply(
            new RentStarted(
                $this->userId,
                $rentId,
                $startedAd
            )
        );
    }

    protected function applyRentStarted(RentStarted $event): void
    {
        $this->rentsStarted[$event->rentId()->toString()] = $event->startedAt();
    }

    public function rentCompleteWithData(
        ActionId $rentId,
        CompletedAt $completedAt,
        ActionBonusPoints $actionBonusPointsPerDay
    ): void {
        $this->apply(
            new RentCompleted(
                $this->userId,
                $rentId,
                $completedAt,
                $actionBonusPointsPerDay
            )
        );
    }

    protected function applyRentCompleted(RentCompleted $event): void
    {
        $rentId = $event->rentId()->toString();
        if (!array_key_exists($rentId, $this->rentsStarted)) {
            //TODO You could emit domain event here instead
            throw new \RuntimeException(sprintf(
                "Trying to complete rent with id: %s that was not started",
                $event->rentId()->toString()
            ));
        }

        $rentDuration = RentDuration::fromStartedAtAndCompletedAt(
            $this->rentsStarted[$rentId], $event->completedAt()
        );

        $this->rentsCompleted[$event->rentId()->toString()] = $rentDuration;
        unset($this->rentsStarted[$event->rentId()->toString()]);

        $this->userBonusPoints = UserBonusPoints::addActionBonusPoints(
            $this->userBonusPoints, $event->actionBonusPoints()->multiplyBy($rentDuration->fullDays())
        );
    }

    public function boosterActivateWithData(
        UserId $userId,
        BoosterId $boosterId,
        BoosterAppliedAt $appliedAt,
        BoosterValidFrom $validFrom,
        BoosterValidTo $validTo,
        BoosterApplicableForAction $applicableForAction,
        BoosterBonusPoints $boosterBonusPoints,
        BoosterActionsRequired $boosterActionsRequired
    ): void {
        $this->apply(
            new BoosterActivated(
                $userId,
                $boosterId,
                $appliedAt,
                $validFrom,
                $validTo,
                $applicableForAction,
                $boosterBonusPoints,
                $boosterActionsRequired
            )
        );
    }

    public function applyBoosterActivated(BoosterActivated $event)
    {
        $this->activeBoosters[$event->applicableForAction()->toString()] = [
            Booster::fromValueObjects(
                $event->appliedAt(),
                $event->validFrom(),
                $event->validTo(),
                $event->applicableForAction(),
                $event->boosterBonusPoints(),
                $event->boosterActionsRequired()
            )
        ];
    }
}
