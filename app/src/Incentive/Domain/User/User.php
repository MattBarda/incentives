<?php

namespace App\Incentive\Domain\User;

use App\Incentive\Domain\User\DomainEvent\BoosterActivated;
use App\Incentive\Domain\User\DomainEvent\BoosterApplied;
use App\Incentive\Domain\User\DomainEvent\DeliveryCompleted;
use App\Incentive\Domain\User\DomainEvent\RentCompleted;
use App\Incentive\Domain\User\DomainEvent\RentStarted;
use App\Incentive\Domain\User\DomainEvent\RideShareCompleted;
use App\Incentive\Domain\User\DomainEvent\UserWasRegistered;
use App\Incentive\Domain\User\ValueObject\ActionBonusPoints;
use App\Incentive\Domain\User\ValueObject\Booster;
use App\Incentive\Domain\User\ValueObject\BoosterActionsRequired;
use App\Incentive\Domain\User\ValueObject\BoosterActiveRange;
use App\Incentive\Domain\User\ValueObject\BoosterApplicableForAction;
use App\Incentive\Domain\User\ValueObject\BoosterAppliedAt;
use App\Incentive\Domain\User\ValueObject\BoosterBonusPoints;
use App\Incentive\Domain\User\ValueObject\BoosterId;
use App\Incentive\Domain\User\ValueObject\CompletedAt;
use App\Incentive\Domain\User\ValueObject\ActionId;
use App\Incentive\Domain\User\ValueObject\EmailAddress;
use App\Incentive\Domain\User\ValueObject\ExpirationDate;
use App\Incentive\Domain\User\ValueObject\ExpiringUserBonusPoints;
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
    private array $deliveriesApplicableForBooster = [];
    private array $completedRideShares = [];
    private array $rideSharesApplicableForBooster = [];
    private array $rentsStarted = [];
    private array $rentsCompleted = [];
    private array $activeBoosters = [];
    private array $expiringBonusPints = [];

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

        $isBoosterActive = array_key_exists('delivery', $this->activeBoosters);
        if ($isBoosterActive) {

            /** @var Booster $activeBooster */
            $activeBooster = $this->activeBoosters['delivery'][0];

            $isDeliveryInBoosterRange = $completedAt->toCarbon()->between(
                $activeBooster->activeRange()->boosterActiveFrom()->toCarbon(),
                $activeBooster->activeRange()->boosterActiveTo()->toCarbon()
            );

            if ($isDeliveryInBoosterRange) {
                if (count($this->deliveriesApplicableForBooster) === $activeBooster->boosterActionsRequired()->toInt()) {

                    $this->apply(
                        new BoosterApplied(
                            $this->userId,
                            ExpiringUserBonusPoints::fromUserBonusPointsAndExpirationDate(
                                UserBonusPoints::fromBoosterBonusPoints($activeBooster->boosterBonusPoints()),
                                ExpirationDate::fromBoosterBonusPoints($activeBooster->boosterBonusPoints()),
                            ),
                            $activeBooster->applicableForAction()
                        )
                    );

                }
            }
        }
    }

    protected function applyDeliveryCompleted(DeliveryCompleted $event): void
    {
        $this->completedDeliveries[$event->deliveryId()->toString()] = $event->completedAt();

        $this->userBonusPoints = UserBonusPoints::addActionBonusPoints(
            $this->userBonusPoints, $event->actionBonusPoints()
        );

        $isBoosterActive = array_key_exists('delivery', $this->activeBoosters);
        if ($isBoosterActive) {

            /** @var Booster $activeBooster */
            $activeBooster = $this->activeBoosters['delivery'][0];

            $isDeliveryInBoosterRange = $event->completedAt()->toCarbon()->between(
                $activeBooster->activeRange()->boosterActiveFrom()->toCarbon(),
                $activeBooster->activeRange()->boosterActiveTo()->toCarbon()
            );

            if ($isDeliveryInBoosterRange) {
                $this->deliveriesApplicableForBooster[$event->deliveryId()->toString()] = $event->deliveryId()->toString();

            } else {
                $this->deliveriesApplicableForBooster = [];
            }
        }
    }

    protected function applyBoosterApplied(BoosterApplied $event)
    {
        $this->expiringBonusPints[] = $event->expiringUserBonusPoints();
        $this->deliveriesApplicableForBooster = [];
        $this->rideSharesApplicableForBooster = [];
    }

    public function completeRideShareWithData(
        ActionId $rideShareId,
        CompletedAt $completedAt,
        ActionBonusPoints $actionBonusPoints
    ): void {
        $this->apply(
            new RideShareCompleted(
                $this->userId,
                $rideShareId,
                $completedAt,
                $actionBonusPoints
            )
        );

        $isBoosterActive = array_key_exists('rideShare', $this->activeBoosters);
        if ($isBoosterActive) {

            /** @var Booster $activeBooster */
            $activeBooster = $this->activeBoosters['rideShare'][0];

            $isRideShareInBoosterRange = $completedAt->toCarbon()->between(
                $activeBooster->activeRange()->boosterActiveFrom()->toCarbon(),
                $activeBooster->activeRange()->boosterActiveTo()->toCarbon()
            );

            if ($isRideShareInBoosterRange) {
                if (count($this->rideSharesApplicableForBooster) === $activeBooster->boosterActionsRequired()->toInt()) {

                    $this->apply(
                        new BoosterApplied(
                            $this->userId,
                            ExpiringUserBonusPoints::fromUserBonusPointsAndExpirationDate(
                                UserBonusPoints::fromBoosterBonusPoints($activeBooster->boosterBonusPoints()),
                                ExpirationDate::fromBoosterBonusPoints($activeBooster->boosterBonusPoints()),
                            ),
                            $activeBooster->applicableForAction()
                        )
                    );

                }
            }
        }
    }

    protected function applyRideShareCompleted(RideShareCompleted $event): void
    {
        $this->completedRideShares[$event->rideShareId()->toString()] = $event->completedAt();

        $this->userBonusPoints = UserBonusPoints::addActionBonusPoints(
            $this->userBonusPoints, $event->actionBonusPoints()
        );

        $isBoosterActive = array_key_exists('rideShare', $this->activeBoosters);
        if ($isBoosterActive) {

            /** @var Booster $activeBooster */
            $activeBooster = $this->activeBoosters['rideShare'][0];

            $isRideShareInBoosterRange = $event->completedAt()->toCarbon()->between(
                $activeBooster->activeRange()->boosterActiveFrom()->toCarbon(),
                $activeBooster->activeRange()->boosterActiveTo()->toCarbon()
            );

            if ($isRideShareInBoosterRange) {
                $this->rideSharesApplicableForBooster[$event->rideShareId()->toString()] = $event->rideShareId()->toString();

            } else {
                $this->rideSharesApplicableForBooster = [];
            }
        }
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
        if (!array_key_exists($rentId->toString(), $this->rentsStarted)) {
            //TODO You could emit domain event here instead
            throw new \RuntimeException(sprintf(
                "Trying to complete rent with id: %s that was not started",
                $rentId->toString()
            ));
        }
        $rentDuration = RentDuration::fromStartedAtAndCompletedAt(
            $this->rentsStarted[$rentId->toString()], $completedAt
        );
        $actionBonusPoints = $actionBonusPointsPerDay->multiplyBy($rentDuration->fullDays());

        $this->apply(
            new RentCompleted(
                $this->userId,
                $rentId,
                $actionBonusPointsPerDay,
                $rentDuration,
                $actionBonusPoints
            )
        );

    }

    protected function applyRentCompleted(RentCompleted $event): void
    {
        $this->rentsCompleted[$event->rentId()->toString()] = $event->rentDuration();
        unset($this->rentsStarted[$event->rentId()->toString()]);

//        $userBonusPoints = UserBonusPoints::addActionBonusPoints(
//            $this->userBonusPoints, $event->actionBonusPoints()
//        );
//
//        $this->userBonusPoints = $userBonusPoints;
    }

    public function boosterActivateWithData(
        UserId $userId,
        BoosterId $boosterId,
        BoosterAppliedAt $appliedAt,
        BoosterActiveRange $activeRange,
        BoosterApplicableForAction $applicableForAction,
        BoosterBonusPoints $boosterBonusPoints,
        BoosterActionsRequired $boosterActionsRequired
    ): void {
        $this->apply(
            new BoosterActivated(
                $userId,
                $boosterId,
                $appliedAt,
                $activeRange,
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
                $event->activeRange(),
                $event->applicableForAction(),
                $event->boosterBonusPoints(),
                $event->boosterActionsRequired()
            )
        ];
    }
}
