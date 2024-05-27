<?php

namespace App\Tests\ApiDataLoader\Transformer;

use App\ApiDataLoader\Loader\ApiFeiertage;
use App\ApiDataLoader\Loader\Response;
use App\ApiDataLoader\Transformer\ApiFeiertageTransformer;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ApiFeiertageTransformerTest extends TestCase
{
    private $loaderData;
    private $response;

    public function setUp(): void
    {
        $this->loaderData = file_get_contents(realpath(__DIR__ . '/fixtures/ApiFeiertageLoaderSuccess.json'));
        $this->response = new Response(true, 200, $this->loaderData, json_decode($this->loaderData, true));
    }

    /**
     * @dataProvider getFailTransformData
     */
    public function testTransformFail($response): void
    {
        $sut = new ApiFeiertageTransformer();
        Assert::assertEquals([], $sut->__invoke($response));
    }

    public function getFailTransformData()
    {
        return
            [
                'failNoSuccess' =>  [new Response(false, 404, '{}', [])],
                'failNoDataKeyEmpty' => [new Response(true, 200, '{}', [])],
                'failNoDataKey' => [new Response(true, 200, '{}', ['some' => 'data'])],
            ];
    }

    public function testGetType(): void
    {
        $sut = new ApiFeiertageTransformer();
        $this->assertEquals(ApiFeiertage::LOADER_TYPE, $sut->getType());
    }

    public function testTransformData(): void
    {
        $sut = new ApiFeiertageTransformer();
        $result = $sut($this->response);
        Assert::assertEquals(3, count($result));
        Assert::assertEquals('Heilige Drei Könige', $result[1]['name']);
        Assert::assertEquals(3, count($result[1]['regions']));
    }
}