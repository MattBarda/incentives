<?php

namespace App\Tests\Incentive\Domain\User\ValueObject;

use App\Incentive\Domain\User\Exception\BoosterRangeException;
use App\Incentive\Domain\User\ValueObject\BoosterActiveFrom;
use App\Incentive\Domain\User\ValueObject\BoosterActiveRange;
use App\Incentive\Domain\User\ValueObject\BoosterActiveTo;
use PHPUnit\Framework\TestCase;

class BoosterActiveRangeTest extends TestCase
{
    public function testItCreatesActiveRangeCorrectly(): void
    {
        $sut = BoosterActiveRange::fromActiveFromAndActiveTo(
            BoosterActiveFrom::fromString("2022-08-26 20:00:00"),
            BoosterActiveTo::fromString("2022-08-27 20:00:00")
        );

        $this->assertEquals("2022-08-26 20:00:00", $sut->boosterActiveFrom()->toString());
        $this->assertEquals("2022-08-27 20:00:00", $sut->boosterActiveTo()->toString());
        $this->assertEquals('1', $sut->boosterDurationAsDateInterval()->d);
    }

    public function testItFailsWhenActiveToBeforeActiveFrom(): void
    {
        $this->expectException(BoosterRangeException::class);
        BoosterActiveRange::fromActiveFromAndActiveTo(
            BoosterActiveFrom::fromString("2022-08-28 20:00:00"),
            BoosterActiveTo::fromString("2022-08-27 20:00:00")
        );
    }
}
