<?php

namespace App\Tests\Incentive\Domain\User\CommandHandler;

use App\Incentive\Domain\User\CommandHandler\DeliveryCompleteHandler;
use App\Incentive\Infrastructure\Repository\EventStore\EventStoreUserRepository;
use Broadway\CommandHandling\CommandHandler;
use Broadway\CommandHandling\Testing\CommandHandlerScenarioTestCase;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\EventStore;

class DeliveryCompleteHandlerTest extends CommandHandlerScenarioTestCase
{
    protected int $deliveryBonusPoints;

    public function setUp(): void
    {
        $this->deliveryBonusPoints = 2;
        parent::setUp();
    }
    
    /**
     * {@inheritdoc}
     */
    protected function createCommandHandler(EventStore $eventStore, EventBus $eventBus): CommandHandler
    {
        return new DeliveryCompleteHandler(
            new EventStoreUserRepository($eventStore, $eventBus),
            $this->deliveryBonusPoints
        );
    }
}
