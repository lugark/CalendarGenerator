<?php

use App\ApiDataLoader\Loader\ApiFeiertage;
use App\ApiDataLoader\Loader\CurlRequest;
use App\ApiDataLoader\Loader\Response;
use App\ApiDataLoader\Transformer\ApiFeiertageTransformer;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ApiFeiertageLoaderTest extends TestCase
{
    private $transformerMock;
    private $curlRequestMock;
    private $sut;

    public function setUp(): void
    {
        $this->curlRequestMock = $this->getMockBuilder(CurlRequest::class)->getMock();
        $this->transformerMock = $this->getMockBuilder(ApiFeiertageTransformer::class)->getMock();
        $this->sut = new ApiFeiertage($this->curlRequestMock, $this->transformerMock);
    }

    public function testGetType(): void
    {
        Assert::assertEquals(ApiFeiertage::LOADER_TYPE, $this->sut->getType());
    }

    public function testGetTransformer(): void
    {
        Assert::assertEquals($this->transformerMock, $this->sut->getTransformer());
    }

    public function testLoad(): void
    {
        $this->curlRequestMock->method('execute')
            ->willReturn(new Response(true, 200, 'TestSuccess', []));
        $result = $this->sut->fetchData('2020');
        Assert::assertEquals('TestSuccess', $result->getResponse());
    }
}