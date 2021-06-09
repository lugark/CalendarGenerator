<?php

namespace App\Tests\ApiDataLoader\Loader;

use App\ApiDataLoader\Loader\CurlLoaderTrait;
use PHPUnit\Framework\TestCase;
use App\ApiDataLoader\Loader\CurlRequest;
use App\ApiDataLoader\Loader\Response;

class StubCurlLoaderTrait
{
    use CurlLoaderTrait;

    public function fetch(string $year): Response
    {
        return $this->executeCurl('http://google.de', []);
    }

    public function getType(): String
    {
        return 'TestStub';
    }
}

class CurlLoaderTraitTest extends TestCase
{
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

        $this->sut = new StubCurlLoaderTrait($this->curlRequestMock);
        $result = $this->sut->fetch('2020');
        $this->assertEquals(new Response(true, 200, '{"test": true}', ['test' => true]), $result);
    }

    public function testFetchFailRequestError()
    {
        $this->curlRequestMock->method('getLastError')
            ->willReturn('ERR!!');
        $this->curlRequestMock->method('getLastErrorCode')
            ->willReturn(404);

        $this->sut = new StubCurlLoaderTrait($this->curlRequestMock);
        $result = $this->sut->fetch('2020');
        $this->assertEquals(new Response(false, 404, 'ERR!!', []), $result);
    }

    public function testFetchFailJsonError()
    {
        $this->curlRequestMock->method('exec')
            ->willReturn('Permanently moved');
        $this->curlRequestMock->method('getInfo')
            ->willReturn(301);

        $this->sut = new StubCurlLoaderTrait($this->curlRequestMock);
        $result = $this->sut->fetch('2020');
        $this->assertEquals(new Response(false, 301, 'Permanently moved', []), $result);
    }

    public function testHttpResponseFailError()
    {
        $this->curlRequestMock->method('exec')
            ->willReturn('Invalid-JSON');
        $this->curlRequestMock->method('getInfo')
            ->willReturn(200);

        $this->sut = new StubCurlLoaderTrait($this->curlRequestMock);
        $result = $this->sut->fetch('2020');
        $this->assertEquals(new Response(false, 4, 'Syntax error', []), $result);
    }
}