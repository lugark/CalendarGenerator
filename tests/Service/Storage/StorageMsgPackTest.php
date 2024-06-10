<?php

namespace App\Tests\Service\Storage;

use App\Service\Storage\Reader\MsgPackReader;
use App\Service\Storage\Storage;
use App\Service\Storage\Writer\MsgPackWriter;
use MessagePack\MessagePack;
use PHPUnit\Framework\TestCase;

class StorageMsgPackTest extends TestCase
{
    /**
     * @var Storage
     */
    protected $sut;

    public static function setUpBeforeClass(): void
    {
        $public = json_decode(file_get_contents(__DIR__ . '/fixture/publicHolidays.json'), true);
        $f = fopen(__DIR__ . '/fixture/publicHolidays.mpack', 'w+b');
        $packedData = MessagePack::pack($public);
        fwrite($f, $packedData, strlen($packedData));
        fclose($f);

        $school = json_decode(file_get_contents(__DIR__ . '/fixture/schoolHolidays.json'), true);
        $f = fopen(__DIR__ . '/fixture/schoolHolidays.mpack', 'w+b');
        $packedData = MessagePack::pack($school);
        fwrite($f, $packedData, strlen($packedData));
        fclose($f);
    }

    public function setUp(): void
    {
        array_map('unlink', glob("/tmp/*" . MsgPackWriter::FILE_ENDING));
        $this->sut = new Storage(new MsgPackWriter(), new MsgPackReader());
        $this->sut->setDataPath(realpath(__DIR__ . '/fixture/'));
    }

    public function tearDown(): void
    {
        $this->sut = null;
    }

    public function dataProviderSchoolHolidays()
    {
        return [
            [
                'BE',
                [[
                    'name' => "Winterferien",
                    'start' => "01.02.2021",
                    'end' => "06.02.2021",
                ]],
            ],
            [
                'HB',
                [[
                    'name' => "Winterferien",
                    'start' => "01.02.2021",
                    'end' => "02.02.2021",
                ]],
            ],
            [
                'BY',
                [],
            ],
            [
                'NONE',
                [],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderSchoolHolidays
     */
    public function testReadSchoolHolidays($federal, $expected): void
    {
        $this->assertEquals($expected, $this->sut->readSchoolHolidays($federal));

    }

    public function dataProviderPublicHolidays()
    {
        return [
            [
                'BE',
                [[
                    'holiday' => [
                        "date" => "2021-01-01",
                        "name" => "Neujahr",
                        "regions" => ["BW", "BY", "BE", "BB", "HB", "HH", "HE", "MV", "NI", "NW", "RP", "SL", "SN", "ST", "SH", "TH"],
                        "all_states" => true,
                    ],
                ]],
            ],
            [
                'HB',
                [[
                    'holiday' => [
                        "date" => "2021-01-01",
                        "name" => "Neujahr",
                        "regions" => ["BW", "BY", "BE", "BB", "HB", "HH", "HE", "MV", "NI", "NW", "RP", "SL", "SN", "ST", "SH", "TH"],
                        "all_states" => true,
                    ],
                ]],
            ],
            [
                'HH',
                [[
                    'holiday' => [
                        "date" => "2021-01-01",
                        "name" => "Neujahr",
                        "regions" => ["BW", "BY", "BE", "BB", "HB", "HH", "HE", "MV", "NI", "NW", "RP", "SL", "SN", "ST", "SH", "TH"],
                        "all_states" => true,
                    ],
                ]],
            ],
            [
                'BY',
                [
                    [
                        'holiday' => [
                            "date" => "2021-01-01",
                            "name" => "Neujahr",
                            "regions" => ["BW", "BY", "BE", "BB", "HB", "HH", "HE", "MV", "NI", "NW", "RP", "SL", "SN", "ST", "SH", "TH"],
                            "all_states" => true,
                        ],
                    ],
                    [
                        'holiday' => [
                            "date" => "2021-11-01",
                            "name" => "Allerheiligen",
                            "regions" => ["BW", "BY", "NW", "RP", "SL"],
                            "all_states" => false,
                        ],
                    ],
                ],
            ],
            [
                'NONE',
                [],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderPublicHolidays
     */
    public function testReadPublicHolidays($federal, $expected): void
    {
        $this->assertEquals($expected, $this->sut->readPublicHolidays($federal));
    }

    public function testWriteSchoolHolidays(): void
    {
        $testData = [
            'test' => true,
        ];
        $testFilename = '/tmp/' . Storage::STORAGE_TYPE_SCHOOL_HOLIDAY . MsgPackWriter::FILE_ENDING;
        $this->sut->setDataPath('/tmp');
        $this->sut->writeSchoolHolidays($testData);
        $this->assertFileExists($testFilename);
        $this->assertEquals(strlen(MessagePack::pack($testData)), filesize($testFilename));
    }

    public function testWritePublicHolidays(): void
    {
        $testData = [
            'test' => true,
        ];
        $testFilename = '/tmp/' . Storage::STORAGE_TYPE_PUBLIC_HOLIDAY . MsgPackWriter::FILE_ENDING;
        $this->sut->setDataPath('/tmp');
        $this->sut->writePublicHolidays($testData);
        $this->assertFileExists($testFilename);
        $this->assertEquals(strlen(MessagePack::pack($testData)), filesize($testFilename));
    }

    public function testFailSetPath(): void
    {
        #$this->markTestSkipped("Needs to be fixed");
        $this->expectExceptionMessage('could not read path: /I-Dont-Exisstsss');
        $this->sut->setDataPath('/I-Dont-Exisstsss');
    }
}
