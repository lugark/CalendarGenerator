<?php

namespace App\Tests\ApiDataLoader\Transformer;

use App\ApiDataLoader\Loader\Response;
use App\ApiDataLoader\Transformer\DeutscheFeiertageApi;
use PHPUnit\Framework\TestCase;

class DeutscheFeiertageApiTransformerTest extends TestCase
{
    public function setUp(): void
    {
    }

    public function testGetType(): void
    {
        $sut = new DeutscheFeiertageApi();
        $this->assertEquals(\App\ApiDataLoader\Loader\DeutscheFeiertageApi::LOADER_TYPE, $sut->getType());
    }

    public function testSuccessTransform(): void
    {
        $responseData = [
            'result' => "success",
            'holidays' => [[
                'holiday' => [
                    'date' => "2018-10-03",
                    'name' => "Tag der Deutschen Einheit",
                    'regions' => [
                        'bw' => true,
                        'bay' => false,
                    ],
                ],
            ]],
        ];
        $expected = [[
            'holiday' => [
                'date' => "2018-10-03",
                'name' => "Tag der Deutschen Einheit",
                'regions' => ['BW'],
            ],
        ]];
        $sut = new DeutscheFeiertageApi();

        $this->assertEquals($expected, $sut->__invoke(new Response(true, 200, '{}', $responseData)));
    }

    public function testTransformFail(): void
    {
        $responseData = [
            'result' => "success",
            'holidays' => [[
                'holiday' => [
                    'date' => "2018-10-03",
                    'name' => "Tag der Deutschen Einheit",
                    'regions' => [
                        'bw' => true,
                        'bay' => false,
                    ],
                ],
            ]],
        ];
        $sut = new DeutscheFeiertageApi();

        $this->assertEquals([], $sut->__invoke(new Response(false, 200, '{}', $responseData)));
        unset($responseData['result']);
        $this->assertEquals([], $sut->__invoke(new Response(true, 200, '{}', $responseData)));
    }
}
