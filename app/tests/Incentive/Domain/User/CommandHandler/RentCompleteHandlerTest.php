<?php

namespace App\Tests\Incentive\Domain\User\CommandHandler;

use App\Incentive\Domain\User\CommandHandler\RentCompleteHandler;
use App\Incentive\Infrastructure\Repository\EventStore\EventStoreUserRepository;
use Broadway\CommandHandling\CommandHandler;
use Broadway\CommandHandling\Testing\CommandHandlerScenarioTestCase;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\EventStore;

class RentCompleteHandlerTest extends CommandHandlerScenarioTestCase
{
    protected int $rentBonusPointsPerFullDay;

    public function setUp(): void
    {
        $this->rentBonusPointsPerFullDay = 2;
        parent::setUp();
    }

    /**
     * {@inheritdoc}
     */
    protected function createCommandHandler(EventStore $eventStore, EventBus $eventBus): CommandHandler
    {
        return new RentCompleteHandler(
            new EventStoreUserRepository($eventStore, $eventBus),
            $this->rentBonusPointsPerFullDay
        );
    }
}
