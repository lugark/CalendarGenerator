<?php

namespace App\Tests\ApiDataLoader\Loader\MehrSchulferien;

use App\ApiDataLoader\Loader\CurlRequest;
use App\ApiDataLoader\Loader\MehrSchulferien\Locations;
use App\ApiDataLoader\Loader\Response;
use PHPUnit\Framework\TestCase;

class LocationsTest extends TestCase
{
    private $curlRequestMock;

    public function setUp(): void
    {
        $this->curlRequestMock = $this->getMockBuilder(CurlRequest::class)
            ->getMock();
    }

    public function testGetApiSubPath(): void
    {
        $sut = new Locations($this->curlRequestMock);
        $this->assertEquals(Locations::LOCATION_PATH, $sut->getApiSubPath());
    }

    public function testGetLocationSuccess(): void
    {
        $jsonFixture = file_get_contents(realpath(__DIR__ . '/fixtures/LocationSuccessResult.json'));
        $expectedResponse = new Response(true, 200, $jsonFixture, json_decode($jsonFixture, true));
        $this->curlRequestMock->method('execute')
            ->willReturn($expectedResponse);

        $sut = new Locations($this->curlRequestMock);
        $result = $sut->getLocation(9);
        $this->assertEquals("Mecklenburg-Vorpommern", $result['name']);
    }
}
