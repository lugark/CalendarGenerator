<?php

namespace App\Renderer;

use App\Calendar\Event;
use App\Renderer\EventTypeRenderer\EventTypeRendererException;
use App\Renderer\EventTypeRenderer\EventTypeRendererInterface;
use Mpdf\Mpdf;

class EventRenderer
{
    /** @var EventTypeRendererInterface[] */
    protected array $renderer = [];

    /** @var Mpdf */
    protected $mpdf;

    public function setPdfRenderClass($pdfClass): void
    {
        $this->mpdf = $pdfClass;
    }

    public function registerRenderer(EventTypeRendererInterface $eventRenderer)
    {
        $eventRenderer->setPdfRendererClass($this->mpdf);
        $this->renderer[$eventRenderer->getRenderType()] = $eventRenderer;
    }

    public function renderEvents(array $events, CalendarRenderInformation $calendarRenderInformation)
    {
        /** @var Event $event */
        foreach ($events as $event) {
            $eventType = $event->getType();
            if (!array_key_exists($eventType, $this->renderer)) {
                throw new EventTypeRendererException(
                    'Can not find renderer for event-type: ' . $eventType
                );
            }

            $this->renderer[$eventType]->render($event, $calendarRenderInformation);
        }
    }

}