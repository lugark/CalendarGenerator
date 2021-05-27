<?php

namespace App\Renderer\EventTypeRenderer;

use App\Calendar\Event;
use App\Renderer\RenderInformation\RenderInformationInterface;

interface EventTypeRendererInterface
{
    public function setPdfRendererClass($pdfClass): void;

    public function render(
        Event $event,
        RenderInformationInterface $calendarRenderInformation
    ): void;

    public function getRenderType(): string;

}