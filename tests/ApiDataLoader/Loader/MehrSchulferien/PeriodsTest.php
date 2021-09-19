<?php

namespace App\Tests\ApiDataLoader\Loader\MehrSchulferien;

use App\ApiDataLoader\Loader\CurlRequest;
use App\ApiDataLoader\Loader\MehrSchulferien\Periods;
use PHPUnit\Framework\TestCase;

class PeriodsTest extends TestCase
{
    private $curlRequestMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->curlRequestMock = $this->getMockBuilder(CurlRequest::class)
            ->getMock();
    }

    public function testGetApiSubPath()
    {
        $sut = new Periods($this->curlRequestMock);
        $this->assertEquals(Periods::PERIODS_PATH, $sut->getApiSubPath());
    }

    public function testFetchAllPeriods()
    {
        $this->curlRequestMock->method('exec')
            ->willReturn(file_get_contents(realpath(__DIR__ . '/fixtures/PeriodsSuccess.json')));
        $this->curlRequestMock->method('getInfo')
            ->willReturn(200);
        $this->curlRequestMock->method('getLastErrorCode')
            ->willReturn(0);

        $sut = new Periods($this->curlRequestMock);
        $response = $sut->getAllPeriods();
        $this->assertTrue($response->isSuccess());
        $this->assertEquals(2, count($response->getData()['data']));
    }
}
