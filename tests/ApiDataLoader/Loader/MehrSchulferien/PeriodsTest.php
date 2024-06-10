<?php

namespace App\Tests\ApiDataLoader\Loader\MehrSchulferien;

use App\ApiDataLoader\Loader\CurlRequest;
use App\ApiDataLoader\Loader\MehrSchulferien\Periods;
use App\ApiDataLoader\Loader\Response;
use PHPUnit\Framework\TestCase;

class PeriodsTest extends TestCase
{
    private $curlRequestMock;

    public function setUp(): void
    {
        $this->curlRequestMock = $this->getMockBuilder(CurlRequest::class)
            ->getMock();
    }

    public function testGetApiSubPath(): void
    {
        $sut = new Periods($this->curlRequestMock);
        $this->assertEquals(Periods::PERIODS_PATH, $sut->getApiSubPath());
    }

    public function testFetchAllPeriods(): void
    {
        $fixture = file_get_contents(realpath(__DIR__ . '/fixtures/PeriodsSuccess.json'));
        $response = new Response(true, 200, $fixture, json_decode($fixture, true));
        $this->curlRequestMock->method('execute')
            ->willReturn($response);

        $sut = new Periods($this->curlRequestMock);
        $response = $sut->getAllPeriods();
        $this->assertTrue($response->isSuccess());
        $this->assertEquals(2, count($response->getData()['data']));
    }
}
