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
   

4. When does additional points for boosters start counting?
5. Do additional points for boosters expire at the end of the day or
    - at the exact time first booster activity started (i.e. first delivery of booster)
    - at the exact time first booster activity stopped (i.e. last delivery of the booster)
    - at the time booster started being active?
    - at the time booster stopped being active?
6. Will it be possible to combine multiple booster actions together in the future?
7. Are we going to need bonus points history/list?
8. Do we need to pay tax on bonus points?

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

## Usage
There are few Symfony commands that allow to interact with the Domain:
