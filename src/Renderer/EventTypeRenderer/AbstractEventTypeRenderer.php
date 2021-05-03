<?php

namespace App\Renderer\EventTypeRenderer;

use App\Renderer\EventRenderer;
use Mpdf\Mpdf;

abstract class AbstractEventTypeRenderer implements EventTypeRendererInterface
{
    protected Mpdf $mpdf;

    public function setPdfRendererClass($pdfClass): void
    {
        $this->mpdf = $pdfClass;
    }
}