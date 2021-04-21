<?php

namespace App\Tests\Repository;

use App\Calendar\Event;
use App\Repository\HolidaysRepository;
use App\Service\Storage\Storage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class HolidaysRepositoryTest extends TestCase
{
    /** @var MockObject */
    protected $storageMock;

    public function setUp()
    {
        parent::setUp();
        $this->storageMock = $this->getMockBuilder(Storage::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function tearDown()
    {
        $this->storageMock = null;
        parent::tearDown();
    }

    public function testSaveSchoolHolidays()
    {
        $this->storageMock->expects($this->once())
            ->method('writeSchoolHolidays')
            ->willReturn(true);
        $sut = new HolidaysRepository($this->storageMock);
        $sut->saveSchoolHolidays([]);
    }

    public function testSavePublicHolidays()
    {
        $this->storageMock->expects($this->once())
            ->method('writePublicHolidays')
            ->willReturn(true);
        $sut = new HolidaysRepository($this->storageMock);
        $sut->savePublicHolidays([]);
    }

    public function testGetPublicHolidaysFound()
    {
        $this->storageMock->method('readPublicHolidays')
            ->willReturn(
                [
                    ["holiday" => ["date" => "2021-01-06", "name" => "Heilige Drei Könige", "regions" => [0 => "BW", 1 => "BY", 2 => "ST"], "all_states" => false]],
                    ["holiday" => ["date" => "2021-11-01", "name" => "Allerheiligen", "regions" => [0 => "BW", 1 => "BY", 2 => "NW", 3 => "RP", 4 => "SL"], "all_states" => false]],
                ]);

        $sut = new HolidaysRepository($this->storageMock);
        $holidays = $sut->getPublicHolidays('BY');
        $this->assertEquals(2, count($holidays));

        $holiday = $holidays[1];
        $this->assertInstanceOf(Event::class, $holiday);
        $this->assertEquals('Allerheiligen', $holiday->getText());
    }

    public function testGetSchoolHolidays()
    {
        $this->storageMock->method('readSchoolHolidays')
            ->willReturn(
                [
                    ["name" => "Osterferien", "start" => "29.03.2021", "end" => "10.04.2021"],
                    ["name" => "Pfingstferien","start" => "25.05.2021","end" => "04.06.2021"],
                    ["name" => "Sommerferien","start" => "30.07.2021","end" => "13.09.2021"],
                    ["name" => "Herbstferien","start" => "02.11.2021","end" => "17.11.2021"],
                    ["name" => "Weihnachtsferien","start" => "24.12.2021","end" => "08.01.2022"]
                ]);

        $sut = new HolidaysRepository($this->storageMock);
        $holidays = $sut->getSchoolHolidays('BY');
        $this->assertEquals(5, count($holidays));

        $holiday = $holidays[1];
        $this->assertInstanceOf(Event::class, $holiday);
        $this->assertEquals('Pfingstferien', $holiday->getText());
    }
}
