<?php

namespace App\Renderer;

interface RendererInterface
{
    public function renderCalendar(string $file = ''): ?string;
    public function setCalendarData($calendarData): void;
    public function setCalendarEvents($events): void;
}
