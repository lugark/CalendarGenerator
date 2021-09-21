<?php

namespace App\Tests\ApiDataLoader\Transformer;

use App\ApiDataLoader\Loader\MehrSchulferienApi;
use App\ApiDataLoader\Loader\Response;
use App\ApiDataLoader\Transformer\MehrSchulferien;
use PHPUnit\Framework\TestCase;

class MehrSchulferienTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testGetType()
    {
        $sut = new MehrSchulferien();
        $this->assertEquals(MehrSchulferienApi::LOADER_TYPE, $sut->getType());
    }

    public function testTransformData()
    {
        $loaderData = file_get_contents(realpath(__DIR__ . '/fixtures/MehrSchulferienLoaderSuccess.json'));
        $response = new Response(true, 200, $loaderData, json_decode($loaderData, true));
        $sut = new MehrSchulferien();
        $result = $sut($response);
        $this->assertEquals(1, count($result));
        $this->assertEquals(3, count($result[0]));
        $this->assertEquals('Herbstferien', $result[0]['name']);
        $this->assertEquals(['start' => "2020-03-16", 'end' => "2020-04-03"], $result[0]['BY']);
        $this->assertEquals(['start' => "2020-04-16", 'end' => "2020-04-20"], $result[0]['BB']);
    }
}
