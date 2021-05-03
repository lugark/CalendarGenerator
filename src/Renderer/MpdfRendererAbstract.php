<?php

namespace App\Renderer;

use App\Calendar\Calendar;
use Mpdf\Mpdf;

abstract class MpdfRendererAbstract implements RendererInterface
{
    /**
     * @var int
     */
    protected $marginLeft = 5;
    /**
     * @var int
     */
    protected $marginRight = 5;

    /**
     * @var float
     */
    protected $calenderStartY = 20;

    /**
     * @var int
     */
    protected $headerHeight = 6;

    /** @var Mpdf */
    protected $mpdf;

    /** @var Calendar */
    protected $calendar;

    protected function initMpdf(array $options=[], string $displaymode='fullpage' ): void
    {
        $this->mpdf = new Mpdf($options);

        $this->mpdf->setLogger(new class extends \Psr\Log\AbstractLogger {
            public function log($level, $message, $context=[])
            {
                echo $level . ': ' . $message . PHP_EOL;
            }
        });

        $this->mpdf->SetDisplayMode($displaymode);
        $this->mpdf->SetFontSize(6);
    }

    protected function calculateTableDimensions(int $months=12, int $maxRows=31): CalendarRenderInformation
    {
        if (empty($this->mpdf)) {
            throw new RendererException('Can not find PDF-Class - required to calculate dimensions');
        }

        $canvasSizeX = $this->mpdf->w;
        $canvasSizeY = $this->mpdf->h;

        $renderInformation = new CalendarRenderInformation();
        $renderInformation
            ->setHeaderHeight($this->headerHeight)
            ->setColumnWidth(round(
                ($canvasSizeX-($this->marginLeft+$this->marginRight))/$months,
                3
            ))
            ->setRowHeight(
                round(
                    ($canvasSizeY-($this->calenderStartY+$this->headerHeight))/$maxRows,
                    3
            ))
            ->setLeft($this->mpdf->lMargin)
            ->setTop($this->mpdf->tMargin);

        return $renderInformation;
    }

    public function setCalendar(Calendar $calendar): void
    {
        $this->calendar = $calendar;
    }
}