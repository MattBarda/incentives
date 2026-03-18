Feature: User Registration
  In order to earn incentive points
  As a new user
  I need to be able to register in the system

  Scenario: Register a new user
    Given no users are registered
    When I register a user with id "00000000-0000-0000-0000-000000000001", name "John Doe" and email "john@example.com"
    Then a UserWasRegistered event should be emitted
    And the event should contain user id "00000000-0000-0000-0000-000000000001"
    And the event should contain user name "John Doe"
    And the event should contain email "john@example.com"

  Scenario: Register two distinct users
    Given no users are registered
    When I register a user with id "00000000-0000-0000-0000-000000000001", name "Alice" and email "alice@example.com"
    And I register a user with id "00000000-0000-0000-0000-000000000002", name "Bob" and email "bob@example.com"
    Then 2 UserWasRegistered events should have been emitted
