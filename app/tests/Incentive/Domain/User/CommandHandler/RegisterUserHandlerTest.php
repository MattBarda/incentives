<?php

namespace App\Tests\Incentive\Domain\User\CommandHandler;

use App\Incentive\Domain\User\CommandHandler\RegisterUserHandler;
use App\Incentive\Infrastructure\Repository\EventStore\EventStoreUserRepository;
use Broadway\CommandHandling\CommandHandler;
use Broadway\CommandHandling\Testing\CommandHandlerScenarioTestCase;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\EventStore;

class RegisterUserHandlerTest extends CommandHandlerScenarioTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function createCommandHandler(EventStore $eventStore, EventBus $eventBus): CommandHandler
    {
        return new RegisterUserHandler(
            new EventStoreUserRepository($eventStore, $eventBus)
        );
    }
}
