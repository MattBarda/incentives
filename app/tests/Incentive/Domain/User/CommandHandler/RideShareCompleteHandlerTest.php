<?php

namespace App\Tests\Incentive\Domain\User\CommandHandler;

use App\Incentive\Domain\User\CommandHandler\RideShareCompleteHandler;
use App\Incentive\Infrastructure\Repository\EventStore\EventStoreUserRepository;
use Broadway\CommandHandling\CommandHandler;
use Broadway\CommandHandling\Testing\CommandHandlerScenarioTestCase;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\EventStore;

class RideShareCompleteHandlerTest extends CommandHandlerScenarioTestCase
{
    protected int $rideShareBonusPoints;

    public function setUp(): void
    {
        $this->rideShareBonusPoints = 2;
        parent::setUp();
    }

    /**
     * {@inheritdoc}
     */
    protected function createCommandHandler(EventStore $eventStore, EventBus $eventBus): CommandHandler
    {
        return new RideShareCompleteHandler(
            new EventStoreUserRepository($eventStore, $eventBus),
            $this->rideShareBonusPoints
        );
    }
}
