<?php

namespace App\Renderer;

use App\Renderer\RenderInformation\RenderInformationInterface;

interface RendererInterface
{
    public function renderCalendar(RenderRequest $renderRequest): ?string;
    public function setCalendarEvents($events): void;

    public function initRenderer();
    public function getRenderInformation(): RenderInformationInterface;
}
