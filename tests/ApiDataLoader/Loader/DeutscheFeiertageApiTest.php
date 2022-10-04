<?php

namespace App\Tests\ApiDataLoader\Loader;

use PHPUnit\Framework\TestCase;
use App\ApiDataLoader\Loader\DeutscheFeiertageApi;
use App\ApiDataLoader\Loader\CurlRequest;
use App\ApiDataLoader\Loader\Response;

class DeutscheFeiertageApiTest extends TestCase
{
    /** @var DeutscheFeiertageApi */
    private $sut;

    private $curlRequestMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->curlRequestMock = $this->getMockBuilder(CurlRequest::class)
            ->getMock();
    }

    public function testSuccessFetch()
    {
        $expectedResponse = new Response(true, 200, '{"test": true}', ['test' => true]);
        $this->curlRequestMock->method('execute')
            ->willReturn($expectedResponse);

        $this->sut = new DeutscheFeiertageApi($this->curlRequestMock);
        $result = $this->sut->fetchData('2020');
        $this->assertEquals($expectedResponse, $result);
    }

    public function testGetType()
    {
        $this->sut = new DeutscheFeiertageApi($this->curlRequestMock);
        $this->assertEquals(DeutscheFeiertageApi::LOADER_TYPE, $this->sut->getType());
    }
}
