<?php

namespace App\Tests\ApiDataLoader\Loader\MehrSchulferien;

use App\ApiDataLoader\Loader\CurlRequest;
use App\ApiDataLoader\Loader\MehrSchulferien\Locations;
use PHPUnit\Framework\TestCase;

class LocationsTest extends TestCase
{
    private $curlRequestMock;

    public function setUp()
    {
        parent::setUp();
        $this->curlRequestMock = $this->getMockBuilder(CurlRequest::class)
            ->getMock();
    }

    public function testGetApiSubPath()
    {
        $sut = new Locations($this->curlRequestMock);
        $this->assertEquals(Locations::LOCATION_PATH, $sut->getApiSubPath());
    }

    public function testGetLocationSuccess()
    {
        $this->curlRequestMock->method('exec')
            ->willReturn(file_get_contents(realpath(__DIR__ . '/fixtures/LocationSuccessResult.json')));
        $this->curlRequestMock->method('getInfo')
            ->willReturn(200);
        $this->curlRequestMock->method('getLastErrorCode')
            ->willReturn(0);

        $sut = new Locations($this->curlRequestMock);
        $result = $sut->getLocation(9);
        $this->assertEquals("Mecklenburg-Vorpommern", $result['name']);
    }
}
