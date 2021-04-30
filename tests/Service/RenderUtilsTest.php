<?php

namespace App\Tests\Service;

use App\Service\RenderUtils;
use PHPUnit\Framework\TestCase;

class RenderUtilsTest extends TestCase
{

    public function hex2rgbProvider()
    {
        return [
            [
                '#ffffff',
                [255,255,255]
            ],
            [
                '#FFFFFF',
                [255,255,255]
            ],
            [
                'FFF',
                [255,255,255]
            ],
            [
                '0a0b0c',
                [10,11,12]
            ]

        ];
    }

    /** @dataProvider hex2rgbProvider */
    public function testHex2rgb($hex, $rgbArray)
    {
        $this->assertEquals($rgbArray, RenderUtils::hex2rgb($hex));
    }
}
