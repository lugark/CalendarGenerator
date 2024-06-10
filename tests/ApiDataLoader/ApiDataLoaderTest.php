<?php

namespace App\Tests\ApiDataLoader;

use PHPUnit\Framework\TestCase;
use App\ApiDataLoader\ApiDataLoader;
use App\ApiDataLoader\DataLoaderException;
use App\ApiDataLoader\Loader\DeutscheFeiertageApi;
use App\ApiDataLoader\Loader\LoaderInterface;
use App\ApiDataLoader\Loader\Response;
use App\ApiDataLoader\Transformer\DeutscheFeiertageApi as TransformerDeutscheFeiertageApi;

use App\Service\FederalService;

class ApiDataLoaderTest extends TestCase
{
    /** @var ApiDataLoader */
    private $sut;

    private $loaderMock;
    private $transformMock;

    public function setUp(): void
    {
        $this->loaderMock = $this->getMockBuilder(LoaderInterface::class)
            ->getMock();
        $this->transformMock = $this->getMockBuilder(TransformerDeutscheFeiertageApi::class)
            ->getMock();
    }

    public function testSuccessFetchWithoutTransformer(): void
    {
        // Test without transformer
        $this->loaderMock->method("getType")
            ->will($this->returnValue('deutsche_feiertage_api'));
        $this->loaderMock->method('fetchData')
            ->willReturn(new Response(true, 200, '{}', ['test'=>true]));
        $this->loaderMock->method('getTransformer')
            ->willReturn(null);

        $this->sut = new ApiDataLoader([$this->loaderMock]);
        $data = $this->sut->fetchData(DeutscheFeiertageApi::LOADER_TYPE, '2020');
        $this->assertEquals(['test'=>true], $data);
    }

    public function testSuccessFetchWithTransformer(): void
    {
        // Test without transformer
        $this->transformMock->expects($this->once())
            ->method('__invoke')
            ->willReturn(['test'=>'again']);
        $this->loaderMock->method("getType")
            ->will($this->returnValue('deutsche_feiertage_api'));
        $this->loaderMock->method('fetchData')
            ->willReturn(new Response(true, 200, '{}', ['test'=>true]));
        $this->loaderMock->method('getTransformer')
            ->willReturn($this->transformMock);

        $this->sut = new ApiDataLoader([$this->loaderMock]);
        $data = $this->sut->fetchData(DeutscheFeiertageApi::LOADER_TYPE, '2020');
        $this->assertEquals(['test'=>'again'], $data);
    }

    public function testFetchFailNoLoader(): void
    {
        $this->expectException(DataLoaderException::class);
        $this->expectExceptionMessage('Can not find api-loader for deutsche_feiertage_api');

        $this->sut = new ApiDataLoader([$this->loaderMock]);
        $data = $this->sut->fetchData(DeutscheFeiertageApi::LOADER_TYPE, '2020');
    }

    public function testFetchFailRequest(): void
    {
        $this->expectException(DataLoaderException::class);
        $this->expectExceptionMessage('Error loading data: ');

        $this->loaderMock->method("getType")
            ->will($this->returnValue('deutsche_feiertage_api'));
        $this->loaderMock->method('fetchData')
            ->willReturn(new Response(false, 404, 'notFound', []));

        $this->sut = new ApiDataLoader([$this->loaderMock]);
        $data = $this->sut->fetchData(DeutscheFeiertageApi::LOADER_TYPE, '2020');
    }
}
