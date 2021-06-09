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

    public function setUp()
    {
        parent::setUp();
        $this->curlRequestMock = $this->getMockBuilder(CurlRequest::class)
            ->getMock();
    }

    public function testSuccessFetch()
    {
        $this->curlRequestMock->method('exec')
            ->willReturn('{"test": true}');
        $this->curlRequestMock->method('getInfo')
            ->willReturn(200);
        $this->curlRequestMock->method('getLastErrorCode')
            ->willReturn(0);

        $this->sut = new DeutscheFeiertageApi($this->curlRequestMock);
        $result = $this->sut->fetch('2020');
        $this->assertEquals(new Response(true, 200, '{"test": true}', ['test' => true]), $result);
    }

    public function testGetType()
    {
        $this->sut = new DeutscheFeiertageApi($this->curlRequestMock);
        $this->assertEquals(DeutscheFeiertageApi::LOADER_TYPE, $this->sut->getType());
    }
}