<?php

namespace App\Tests\ApiDataLoader\Loader\MehrSchulferien;

use App\ApiDataLoader\Loader\CurlRequest;
use App\ApiDataLoader\Loader\MehrSchulferien\Types;
use PHPUnit\Framework\TestCase;

class TypesTest extends TestCase
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
        $sut = new Types($this->curlRequestMock);
        $this->assertEquals(Types::TYPES_PATH, $sut->getApiSubPath());
    }

    public function testFetchAll()
    {
        $this->curlRequestMock->method('exec')
            ->willReturn(file_get_contents(realpath(__DIR__ . '/fixtures/TypesSuccessResult.json')));
        $this->curlRequestMock->method('getInfo')
            ->willReturn(200);
        $this->curlRequestMock->method('getLastErrorCode')
            ->willReturn(0);

        $sut = new Types($this->curlRequestMock);
        $result = $sut->getType(2);
        $this->assertEquals(5, count($result));
        $this->assertEquals("Weihnachten", $result['name']);
    }
}
