<?php

namespace App\Renderer\EventTypeRenderer\LandscapeYear;

use App\Calendar\Event;
use App\Calendar\Event\Types;
use App\Renderer\CalendarRenderInformation;
use App\Renderer\EventTypeRenderer\AbstractEventTypeRenderer;

class PublicHolidayRenderer extends AbstractEventTypeRenderer
{
    const FONT_SIZE_HOLIDAY = 5;

    public function render(Event $event, CalendarRenderInformation $calendarRenderInformation): void
    {
        $this->mpdf->SetFontSize(self::FONT_SIZE_HOLIDAY);
        $this->mpdf->SetFont('', 'B');
        $this->mpdf->SetTextColor(199, 50, 50);

        $month = $event->getStart()->format('m');
        $day = $event->getStart()->format('d');

        $x = $calendarRenderInformation->getLeft() + (($month-1) * $calendarRenderInformation->getColumnWidth());
        $y = $calendarRenderInformation->getTop() +
            (($day-1) * $calendarRenderInformation->getRowHeight()) +
            $calendarRenderInformation->getHeaderHeight()  + 1.7;

        $this->mpdf->SetXY($x, $y);
        $this->mpdf->WriteText($x,$y, $event->getText());
    }

    public function getRenderType(): string
    {
        return Types::EVENT_TYPE_PUBLIC_HOLIDAY;
    }
}