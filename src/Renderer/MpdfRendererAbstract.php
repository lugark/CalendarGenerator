<?php

namespace App\Renderer;

use Aeon\Calendar\Gregorian\Month;
use App\Calendar\Calendar;
use App\Renderer\RenderInformation\RenderInformationInterface;
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

    protected EventRenderer $eventRenderer;
    protected RenderRequest $renderRequest;

    public function __construct(RenderRequest $renderRequest, EventRenderer $eventRenderer)
    {
        $this->eventRenderer = $eventRenderer;
        $this->renderRequest = $renderRequest;
        $this->initRenderer();
    }

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

    public function setCalendar(Calendar $calendar): void
    {
        $this->calendar = $calendar;
    }

    protected function calculateDimensions(): RenderInformationInterface
    {
        if (empty($this->mpdf)) {
            throw new RendererException('Can not find PDF-Class - required to calculate dimensions');
        }

        $renderInformation = $this->getRenderInformation()
            ->setCalendarPeriod($this->renderRequest->getPeriod())
            ->initRenderInformation()
            ->setLeft($this->mpdf->lMargin)
            ->setTop($this->mpdf->tMargin);

        return $renderInformation;
    }
}