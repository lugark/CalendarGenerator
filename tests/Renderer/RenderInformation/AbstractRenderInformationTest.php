<?php

namespace App\Tests\Renderer\RenderInformation;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\TimePeriod;
use App\Renderer\RenderInformation\AbstractRenderInformation;
use PHPUnit\Framework\TestCase;

class AbstractRenderInformationTest extends TestCase
{
    protected AbstractRenderInformation $sut;

    public function setUp(): void
    {
        parent::setUp();
        $this->sut = $this->getMockForAbstractClass(AbstractRenderInformation::class);
    }

    public function getPeriodData()
    {
        return [
            [
                new TimePeriod(DateTime::fromString('1-1-1976'), DateTime::fromString('1-2-1976')),
                false,
                DateTime::fromString('1-1-1976'),
                DateTime::fromString('1-2-1976')
            ],
            [
                new TimePeriod(DateTime::fromString('1-1-1976'), DateTime::fromString('1-2-1977')),
                true,
                DateTime::fromString('1-1-1976'),
                DateTime::fromString('1-2-1977')
            ],
        ];
    }

    /** @dataProvider getPeriodData */
    public function testPeriodSettings($period, $expectedCrossYear, $expectedStart, $expectedEnd)
    {
        $this->sut->setCalendarPeriod($period);
        $this->assertEquals($period, $this->sut->getTimePeriod());
        $this->assertEquals($expectedStart, $this->sut->getCalendarStartsAt());
        $this->assertEquals($expectedEnd, $this->sut->getCalendarEndsAt());
        $this->assertEquals($expectedCrossYear, $this->sut->doesCrossYear());
    }
}
