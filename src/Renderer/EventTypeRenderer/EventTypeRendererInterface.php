<?php

namespace App\Renderer\EventTypeRenderer;

use App\Calendar\Event;
use App\Renderer\CalendarRenderInformation;
use App\Renderer\EventRenderer;

interface EventTypeRendererInterface
{
    public function setPdfRendererClass($pdfClass): void;

    public function render(
        Event $event,
        CalendarRenderInformation $calendarRenderInformation
    ): void;

    public function getRenderType(): string;

}