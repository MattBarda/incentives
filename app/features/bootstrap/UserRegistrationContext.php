<?php

use App\Incentive\Domain\User\Command\RegisterUser;
use App\Incentive\Domain\User\CommandHandler\RegisterUserHandler;
use App\Incentive\Domain\User\DomainEvent\UserWasRegistered;
use App\Incentive\Infrastructure\Repository\EventStore\EventStoreUserRepository;
use Behat\Behat\Context\Context;
use Broadway\CommandHandling\SimpleCommandHandler;
use Broadway\EventStore\InMemoryEventStore;
use Broadway\EventHandling\SimpleEventBus;
use Broadway\EventStore\TraceableEventStore;

require_once __DIR__ . '/../../vendor/autoload.php';

class UserRegistrationContext implements Context
{
    private TraceableEventStore $eventStore;
    private RegisterUserHandler $handler;
    /** @var UserWasRegistered[] */
    private array $emittedEvents = [];

    public function __construct()
    {
        $this->eventStore = new TraceableEventStore(new InMemoryEventStore());
        $this->handler = new RegisterUserHandler(
            new EventStoreUserRepository($this->eventStore, new SimpleEventBus())
        );
    }

    /**
     * @Given no users are registered
     */
    public function noUsersAreRegistered(): void
    {
        $this->eventStore->trace();
        $this->emittedEvents = [];
    }

    /**
     * @When I register a user with id :userId, name :name and email :email
     */
    public function iRegisterAUser(string $userId, string $name, string $email): void
    {
        $this->handler->handle(new RegisterUser($userId, $name, $email));

        foreach ($this->eventStore->getEvents() as $event) {
            if ($event instanceof UserWasRegistered) {
                $this->emittedEvents[] = $event;
            }
        }
        $this->eventStore->clearEvents();
    }

    /**
     * @Then a UserWasRegistered event should be emitted
     */
    public function aUserWasRegisteredEventShouldBeEmitted(): void
    {
        if (empty($this->emittedEvents)) {
            throw new \RuntimeException('Expected a UserWasRegistered event but none was emitted.');
        }
    }

    /**
     * @Then the event should contain user id :userId
     */
    public function theEventShouldContainUserId(string $userId): void
    {
        $event = $this->emittedEvents[0];
        if ($event->userId()->toString() !== $userId) {
            throw new \RuntimeException(sprintf(
                'Expected user id "%s" but got "%s".',
                $userId,
                $event->userId()->toString()
            ));
        }
    }

    /**
     * @Then the event should contain user name :name
     */
    public function theEventShouldContainUserName(string $name): void
    {
        $event = $this->emittedEvents[0];
        if ($event->userName()->toString() !== $name) {
            throw new \RuntimeException(sprintf(
                'Expected user name "%s" but got "%s".',
                $name,
                $event->userName()->toString()
            ));
        }
    }

    /**
     * @Then the event should contain email :email
     */
    public function theEventShouldContainEmail(string $email): void
    {
        $event = $this->emittedEvents[0];
        if ($event->emailAddress()->toString() !== $email) {
            throw new \RuntimeException(sprintf(
                'Expected email "%s" but got "%s".',
                $email,
                $event->emailAddress()->toString()
            ));
        }
    }

    /**
     * @Then :count UserWasRegistered events should have been emitted
     */
    public function userWasRegisteredEventsShouldHaveBeenEmitted(int $count): void
    {
        if (count($this->emittedEvents) !== $count) {
            throw new \RuntimeException(sprintf(
                'Expected %d UserWasRegistered event(s) but got %d.',
                $count,
                count($this->emittedEvents)
            ));
        }
    }
}
