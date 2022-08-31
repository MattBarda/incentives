<?php

namespace App\Tests\Incentive\Domain\User\ValueObject;

use App\Incentive\Domain\User\ValueObject\CompletedAt;
use App\Incentive\Domain\User\ValueObject\RentDuration;
use App\Incentive\Domain\User\ValueObject\StartedAt;
use PHPUnit\Framework\TestCase;

class RentDurationTest extends TestCase
{
    public function testItCreatesRentDuration(): void
    {
        $sut = RentDuration::fromStartedAtAndCompletedAt(
            StartedAt::fromString('2022-08-25 20:00:00'),
            CompletedAt::fromString('2022-08-27 20:00:00')
        );

        $this->assertEquals(2, $sut->fullDays());
    }
}
