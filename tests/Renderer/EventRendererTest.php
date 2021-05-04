<?php

namespace App\Tests\Renderer;

use App\Calendar\Event;
use App\Renderer\CalendarRenderInformation;
use App\Renderer\EventRenderer;
use App\Renderer\EventTypeRenderer\EventTypeRendererInterface;
use Mpdf\Mpdf;
use PHPUnit\Framework\TestCase;

class EventRendererTest extends TestCase
{
    /** @var EventRenderer */
    protected $sut;

    /** @var EventRendererInterface */
    protected $mockRenderer;

    public function setUp()
    {
        parent::setUp();
        $mpdfMock = $this->getMockBuilder(Mpdf::class)
                        ->disableOriginalConstructor()
                        ->getMock();

        $this->sut = new EventRenderer();
        $this->sut->setPdfRenderClass($mpdfMock);

        $this->mockRenderer = $this->getMockBuilder(EventTypeRendererInterface::class)
                            ->setMockClassName('TestRenderer')
                            ->getMock();
        $this->mockRenderer->method('getRenderType')
            ->will($this->returnValue('Test'));                        
    }

    public function testRegisterRenderer()
    {
        $this->mockRenderer->expects($this->once())
            ->method('setPdfRendererClass');

        $this->sut->registerRenderer($this->mockRenderer);
    }

    public function testRender()
    {
        $event = new Event('Test');
        $this->mockRenderer->expects($this->once())
            ->method('render');
        
        $this->sut->registerRenderer($this->mockRenderer);
        $this->sut->renderEvents([$event], new CalendarRenderInformation());
    }

    public function testRenderFail()
    {
        $event = new Event('TestFail');
        $this->expectException('App\Renderer\EventTypeRenderer\EventTypeRendererException');
        
        $this->sut->registerRenderer($this->mockRenderer);
        $this->sut->renderEvents([$event], new CalendarRenderInformation());
    }    
}