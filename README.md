## Questions I'd normally ask
As this is a recruitment task I have made certain assumptions, but normally I would start with asking questions about the domain.
1. What is our Bounded context? - I assumed it will be Incentives
2. What is a rideshare?:
    - is a rideshare when the user is a driver or is it when user is a customer? - I assumed the driver
    - if a user is a driver - how do we count rideshares(
        - 1 base customer + 1 additional = 1 rideshare? - I assumed this scenario for simplicity
        - 1 base customer + 1 additional customer = 2 rideshares?
        - 1 base customer + 1 additional + 1 additional = 3 rideshares?)
    - When does the rideshare start and end?
3. Can delivery and a rideshare be a part of one journey? - For simplicity I assumed not
    - if yes do multiple rideshares and deliveries combine?
4. What happens if bonus points awarded for rent change during the rent duration?
   

5. When does additional points for boosters start counting?
6. Do additional points for boosters expire at the end of the day or
    - at the exact time first booster activity started (i.e. first delivery of booster)
    - at the exact time first booster activity stopped (i.e. last delivery of the booster)
    - at the time booster started being active?
    - at the time booster stopped being active?
7. Will it be possible to combine multiple booster actions together in the future?
8. Are we going to need booster points history/list?
9. Are the boosters always active during the same time for each day or are they more dynamic>
10. What happens if booster ranges overlap (1 activated and another one activated after first one)
11. Can a booster have active date range in the past? - I assumend not
12. Can there be multiple boosters active at the same time (for different time ranges)? - I assumed not there is ony one active range at the same time
13. Do we need to pay tax on bonus points?

#### additional questions:

They are worth considering, but they would most likely apply to different bounded context

1. What happens when user withdraws points?
    - are they transferred to users bank account right away?
    - are they exchanged for in-app money balance?
2. Can user withdraw part of their points or does it have to be all pints at once?
3. Will the points ratio change in the future?
4. Is the base for points always going to be USD or different currencies for different regions?

## Installation
In order to install the project:
1. ```cd /incentives```
2. ```docker-compose up -d```
3. ```ctrl+t```
4. ```docker exec -it incentives-php8-container bash```
5. ```composer install```
6. ```bin/console doctrine:databse:create```
7. ```broadway:event-store:create```

## Usage
There are few Symfony commands that allow to interact with the Domain:
 - Create user: ```bin/console incentives:user:create [username] [userEmail]```
 - Complete delivery: ```bin/console incentives:delivery:complete [userId]```
 - Complete rideshare: ```bin/console incentives:rideshare:complete [userId]```
 - Start rent: ```bin/console incentives:rent:start [userId] [OPTIONAL startedAt: "2022-08-25 14:38:09" (in this format)]```
 - Complete rent: ```bin/console incentives:rent:complete [userId] [rentId]```
 - Activate booster: ```bin/console incentives:booster:activate userId [validFrom] [validTo] [applicableForAction] [boosterBonusPoints] [boosterBonusPointsValidFor] [boosterActionsRequired]```

Each of them will return an id needed to interact with other commands

The projections are dumped in app/public, I just didn't have time to properly finish the command to get the number of points for a given date, but the projected data is there


## Process

The general process Can be described in following steps:
1. Read the task and sleep on it :)
2. As I was thinking about the task I was more and more convinced to use the event sourcing approach
3. From the beginning I wanted to use the DDD approach as well
4. First code I wrote was the Symfony command as a way to communicate with the application
5. Then I defined Application service and Application DTO as a layer allowing to comunicate with the domain
6. AS I haven't done event sourcing in a while the next step was research on event sourcing
7. After much thinking I got the basic Event sourcing steps together and defined following commands:
   - registerUser
   - deliveryComplete
   - rideShareComplete
   - rentStart
   - rentEnd
   - boosterActivate
   - boosterApply
   - MoneyWithdrawn
8. Then I created the basic folder structure for Event Sourcing
9. After that I started Thinking about the Aggregate structure
10. And then I created one by one command, command handler, domain event and User aggregate methods to apply events adding Value objects as needed
11. After creating all the domain events, I started creating needed projectors

