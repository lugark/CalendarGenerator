<?php

namespace App\Tests\ApiDataLoader\Loader\MehrSchulferien;

use App\ApiDataLoader\Loader\CurlRequest;
use App\ApiDataLoader\Loader\MehrSchulferien\Types;
use App\ApiDataLoader\Loader\Response;
use PHPUnit\Framework\TestCase;

class TypesTest extends TestCase
{
    private $curlRequestMock;

    public function setUp(): void
    {
        $this->curlRequestMock = $this->getMockBuilder(CurlRequest::class)
            ->getMock();
    }

    public function testGetApiSubPath(): void
    {
        $sut = new Types($this->curlRequestMock);
        $this->assertEquals(Types::TYPES_PATH, $sut->getApiSubPath());
    }

    public function testFetchAll(): void
    {
        $fixture = file_get_contents(realpath(__DIR__ . '/fixtures/TypesSuccessResult.json'));
        $response = new Response(true, 200, $fixture, json_decode($fixture, true));
        $this->curlRequestMock->method('execute')
            ->willReturn($response);

        $sut = new Types($this->curlRequestMock);
        $result = $sut->getType(2);
        $this->assertEquals(5, count($result));
        $this->assertEquals("Weihnachten", $result['name']);
    }
}
