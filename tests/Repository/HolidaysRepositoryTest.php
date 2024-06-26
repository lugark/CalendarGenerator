<?php

namespace App\Tests\Repository;

use App\Repository\HolidaysRepository;
use App\Service\Storage\Storage;
use Calendar\Pdf\Renderer\Event\Event;
use PHPUnit\Framework\TestCase;

class HolidaysRepositoryTest extends TestCase
{
    protected ?Storage $storageMock;

    public function setUp(): void
    {
        $this->storageMock = $this->createMock(Storage::class);
    }

    public function tearDown(): void
    {
        $this->storageMock = null;
    }

    public function testSaveSchoolHolidays(): void
    {
        $this->storageMock->expects($this->once())
            ->method('writeSchoolHolidays');
        $sut = new HolidaysRepository($this->storageMock);
        $sut->saveSchoolHolidays([]);
    }

    public function testSavePublicHolidays(): void
    {
        $this->storageMock->expects($this->once())
            ->method('writePublicHolidays');
        $sut = new HolidaysRepository($this->storageMock);
        $sut->savePublicHolidays([]);
    }

    public function testGetPublicHolidaysFound(): void
    {
        $this->storageMock->method('readPublicHolidays')
            ->willReturn(
                [
                    [
                        "holiday" => [
                            "date" => "2021-01-06",
                            "name" => "Heilige Drei Könige",
                            "regions" => [
                                0 => "BW",
                                1 => "BY",
                                2 => "ST",
                            ],
                            "all_states" => false,
                        ],
                    ],
                    [
                        "holiday" => [
                            "date" => "2021-11-01",
                            "name" => "Allerheiligen",
                            "regions" => [
                                0 => "BW",
                                1 => "BY",
                                2 => "NW",
                                3 => "RP",
                                4 => "SL",
                            ],
                            "all_states" => false,
                        ],
                    ],
                ]
            );

        $sut = new HolidaysRepository($this->storageMock);
        $holidays = $sut->getPublicHolidays('BY');
        $this->assertEquals(2, count($holidays));

        $holiday = $holidays[1];
        $this->assertInstanceOf(Event::class, $holiday);
        $this->assertEquals('Allerheiligen', $holiday->getText());
    }

    public function testGetSchoolHolidays(): void
    {
        $this->storageMock->method('readSchoolHolidays')
            ->willReturn(
                [
                    [
                        "name" => "Osterferien",
                        "start" => "29.03.2021",
                        "end" => "10.04.2021",
                    ],
                    [
                        "name" => "Pfingstferien",
                        "start" => "25.05.2021",
                        "end" => "04.06.2021",
                    ],
                    [
                        "name" => "Sommerferien",
                        "start" => "30.07.2021",
                        "end" => "13.09.2021",
                    ],
                    [
                        "name" => "Herbstferien",
                        "start" => "02.11.2021",
                        "end" => "17.11.2021",
                    ],
                    [
                        "name" => "Weihnachtsferien",
                        "start" => "24.12.2021",
                        "end" => "08.01.2022",
                    ],
                ]
            );

        $sut = new HolidaysRepository($this->storageMock);
        $holidays = $sut->getSchoolHolidays('BY');
        $this->assertEquals(5, count($holidays));

        $holiday = $holidays[1];
        $this->assertInstanceOf(Event::class, $holiday);
        $this->assertEquals('Pfingstferien', $holiday->getText());
    }
}
