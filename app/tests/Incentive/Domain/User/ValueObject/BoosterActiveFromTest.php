<?php

namespace App\Tests\Incentive\Domain\User\ValueObject;

use App\Incentive\Domain\User\ValueObject\BoosterActiveFrom;
use PHPUnit\Framework\TestCase;

class BoosterActiveFromTest extends TestCase
{
    public function testItCreatesActiveFromCorrectly(): void
    {
        $sut = BoosterActiveFrom::fromString("2022-08-27 20:00:00");
        $this->assertEquals("2022-08-27 20:00:00", $sut->toString());
    }
}
