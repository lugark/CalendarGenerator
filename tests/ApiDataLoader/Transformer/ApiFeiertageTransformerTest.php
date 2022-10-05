<?php

namespace App\Tests\ApiDataLoader\Transformer;

use App\ApiDataLoader\Loader\Response;
use App\ApiDataLoader\Transformer\ApiFeiertageTransformer;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ApiFeiertageTransformerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @dataProvider getFailTransformData
     */
    public function testTransformFail($response)
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
}
