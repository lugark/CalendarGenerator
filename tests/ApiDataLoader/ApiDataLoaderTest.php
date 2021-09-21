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
        parent::setUp();
        $this->loaderMock = $this->getMockBuilder(LoaderInterface::class)
            ->getMock();
        $this->transformMock = $this->getMockBuilder(TransformerDeutscheFeiertageApi::class)
            ->getMock();
    }

    public function testSuccessFetch()
    {
        // Test without transformer
        $this->loaderMock->method("getType")
            ->will($this->returnValue('deutsche_feiertage_api'));
        $this->loaderMock->method('fetch')
            ->willReturn(new Response(true, 200, '{}', ['test'=>true]));

        $this->sut = new ApiDataLoader(new FederalService(), [$this->loaderMock], [$this->transformMock]);
        $data = $this->sut->fetchData(DeutscheFeiertageApi::LOADER_TYPE, '2020');
        $this->assertEquals(['test'=>true], $data);

        // Test without transformer
        $this->transformMock->method('getType')
            ->will($this->returnValue('deutsche_feiertage_api'));
        $this->transformMock->expects($this->once())
            ->method('__invoke')
            ->willReturn(['test'=>'again']);

        $this->sut = new ApiDataLoader(new FederalService(), [$this->loaderMock], [$this->transformMock]);
        $data = $this->sut->fetchData(DeutscheFeiertageApi::LOADER_TYPE, '2020');
        $this->assertEquals(['test'=>'again'], $data);
    }

    public function testFetchFailNoLoader()
    {
        $this->expectException(DataLoaderException::class);
        $this->expectExceptionMessage('Can not find api-loader for deutsche_feiertage_api');

        $this->sut = new ApiDataLoader(new FederalService(), [$this->loaderMock], [$this->transformMock]);
        $data = $this->sut->fetchData(DeutscheFeiertageApi::LOADER_TYPE, '2020');
    }

    public function testFetchFailRequest()
    {
        $this->expectException(DataLoaderException::class);
        $this->expectExceptionMessage('Error loading data: ');

        $this->loaderMock->method("getType")
            ->will($this->returnValue('deutsche_feiertage_api'));
        $this->loaderMock->method('fetch')
            ->willReturn(new Response(false, 404, 'notFound', []));

        $this->sut = new ApiDataLoader(new FederalService(), [$this->loaderMock], [$this->transformMock]);
        $data = $this->sut->fetchData(DeutscheFeiertageApi::LOADER_TYPE, '2020');
    }
}