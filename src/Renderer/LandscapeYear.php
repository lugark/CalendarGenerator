<?php

namespace App\Renderer;

use App\Calendar\Event;
use App\Calendar\Unit\Day;
use App\Calendar\Unit\Month;
use App\Renderer\EventTypeRenderer\LandscapeYear\PublicHolidayRenderer;
use App\Renderer\EventTypeRenderer\LandscapeYear\SchoolHolidayRenderer;
use App\Service\RenderUtils;
use Mpdf\Output\Destination;

class LandscapeYear extends MpdfRendererAbstract
{
    CONST FONT_SIZE_HEADER = 8;
    CONST FONT_SIZE_CELL = 6;
    CONST COLOR_TEXT_HEADER = '#c63131';
    const COLOR_BORDER_TABLE = '#c63131';
    CONST COLOR_BORDER_HEADER = '#DEDEDE';
    const COLOR_FILL_SA = '#F8E6E6';
    const COLOR_FILL_SO = '#F3D5D5';

    /** @var Month[] */
    private $calendarData;

    /** @var array<Event> */
    private $calendarEvents;

    private $monthCount = 12;

    private $crossYears = false;

    private $fillColorWeekday = [
        6 => self::COLOR_FILL_SA,
        7 => self::COLOR_FILL_SO
    ];

    protected EventRenderer $eventRenderer;

    public function __construct(EventRenderer $eventRenderer)
    {
        $this->eventRenderer = $eventRenderer;
    }

    public function initRenderer()
    {
        $this->initMpdf([
            'format' => 'A4-L',
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 10,
            'margin_bottom' => 0,
        ]);
        $this->mpdf->AddPage();
        $this->mpdf->SetFont('Helvetica');

        $this->eventRenderer->setPdfRenderClass($this->mpdf);
        $this->eventRenderer->registerRenderer(new SchoolHolidayRenderer());
        $this->eventRenderer->registerRenderer(new PublicHolidayRenderer());
    }

    public function renderCalendar(string $file = ''): ?string
    {
        $this->initRenderer();
        $this->calculateTableDimensions(count($this->calendarData));
        $this->validateCalendarData();
        //TODO: set Calendar object and use decorator to render
        $this->renderHeader();
        $this->renderData();
        $this->renderEvents();

        $redBorder = RenderUtils::hex2rgb(self::COLOR_BORDER_TABLE);
        $this->mpdf->SetDrawColor($redBorder[0], $redBorder[1], $redBorder[2]);
        $this->mpdf->Rect(
            $this->mpdf->lMargin-2,
            $this->mpdf->tMargin,
            $this->monthCount * $this->calenderRenderInformation->getColumnWidth() + 2,
            31 * $this->calenderRenderInformation->getRowHeight() + $this->headerHeight + 2
        );

        if (!empty($file)) {
            $this->mpdf->Output($file, Destination::FILE);
            return '';
        } else {
            return $this->mpdf->Output();
        }
    }

    private function renderHeader()
    {
        $this->mpdf->SetFontSize(self::FONT_SIZE_HEADER);
        $this->mpdf->SetFont('', 'B');
        $borderColor = RenderUtils::hex2rgb(self::COLOR_BORDER_HEADER);
        $textColor = RenderUtils::hex2rgb(self::COLOR_TEXT_HEADER);
        $this->mpdf->SetDrawColor($borderColor[0], $borderColor[1], $borderColor[2]);
        $this->mpdf->SetTextColor($textColor[0], $textColor[1], $textColor[2]);

        /** @var Month $month */
        foreach ($this->calendarData as $key => $month) {
            $text = !$this->crossYears ? $month->getName() : $month->getName() . ' `' .$month->getYear(true);
            $this->mpdf->WriteCell(
                $this->calenderRenderInformation->getColumnWidth() ,
                $this->headerHeight ,
                $text,
                'B',
                0,
                'C'
            );
        }
    }

    public function renderData(): void
    {
        $this->mpdf->SetFontSize(self::FONT_SIZE_CELL);
        $this->mpdf->SetTextColor(0, 0, 0);
        $startHeight = $this->mpdf->tMargin + $this->headerHeight;

        /** @var  Month$month */
        foreach ($this->calendarData as $key => $month) {
            /** @var Day $day */
            foreach ($month->getDays() as $dom => $day) {
                $fill = 0;
                $this->mpdf->SetXY(
                    $this->mpdf->lMargin + ($key * $this->calenderRenderInformation->getColumnWidth() ),
                    $startHeight + (($dom-1) * $this->calenderRenderInformation->getRowHeight() )
                );

                $text = $day->getDay() . ' ' . $day->getWeekdayName();
                $colorData = $this->getDayColorData($day);
                if ($colorData['fill']) {
                    $this->mpdf->SetFillColor($colorData['color'][0], $colorData['color'][1], $colorData['color'][2]);
                }

                $this->mpdf->Cell(
                    $this->calenderRenderInformation->getColumnWidth() -1,
                    $this->calenderRenderInformation->getRowHeight()  ,
                    $text,
                    'B',
                    0,
                    '',
                    $colorData['fill']);
            }
        }
    }

    private function getDayColorData(Day $day): array
    {
        $colorData = [
            'fill' => false,
            'color' => [0,0,0]
        ];

        $dow = $day->getDayOfWeek();
        if ($day->getDayOfWeek() > 5) {
            $colorData['fill'] = 1;
            if (isset($this->fillColorWeekday[$dow])) {
                $colorData['color'] = RenderUtils::hex2rgb($this->fillColorWeekday[$dow]);
            }
        }

        return $colorData;
    }
    private function validateCalendarData():void
    {
        $this->monthCount = count($this->calendarData);
        $this->crossYears = $this->calendarData[0]->getYear() != $this->calendarData[$this->monthCount-1]->getYear();
        $firstDay = $this->calendarData[0]->getFirstDay();
        $lastDay = $this->calendarData[$this->monthCount-1]->getLastDay();
        $this->calendarStartsAt = !empty($firstDay) ? $firstDay->getDate() : 0;
        $this->calendarEndsAt = !empty($lastDay) ? $lastDay->getDate() : 0;
    }

    /**
     * @param mixed $calendarData
     */
    public function setCalendarData($calendarData): void
    {
        $this->calendarData = $calendarData;
    }

    public function setCalendarEvents($events): void
    {
     $this->calendarEvents = $events;
    }

    private function renderEvents():void
    {
        if (empty($this->calendarEvents)) {
            return;
        }

        $this->eventRenderer->renderEvents($this->calendarEvents, $this->calenderRenderInformation);
    }
}