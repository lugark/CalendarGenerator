<?php

namespace App\Renderer;

use Aeon\Calendar\Gregorian\TimePeriod;
use Aeon\Calendar\Gregorian\DateTime;
use App\Renderer\RenderRequest\RequestTypes;
use DateInterval;
use Exception;

class RenderRequest
{
    const DEFAULT_RENDERED_MONTHS = 12;
    const DEFAULT_RENDERED_YEAR = 1;

    protected TimePeriod $period;
    protected string $requestType;
    protected bool $renderToFile = true;
    protected string $renderFile = 'calendar.pdf';

    public function __construct(string $requestType, \DateTime $startDate, \DateTime $endDate = null)
    {
        if (!RequestTypes::isValidRequestType($requestType)) {
            throw new RendererException('Not a valid render request type: ' . $requestType);
        }

        if (empty($endDate)) {
            $endDate = clone $startDate;
            $endDate->add(new DateInterval("P" . self::DEFAULT_RENDERED_MONTHS . "M"));
        }

        $this->requestType = $requestType;
        $this->period = new TimePeriod(
            DateTime::fromDateTime($startDate),
            DateTime::fromDateTime($endDate));
    }

    public function getPeriod(): TimePeriod
    {
        return $this->period;
    }

    public function getRequestType(): string
    {
        return $this->requestType;
    }

    public function getRenderFile():string
    {
        return $this->renderFile;
    }

    public function doRenderToFile(): bool
    {
        return $this->renderToFile;
    }

    public function renderToFile(string $filename): RenderRequest
    {
        $this->renderToFile = true;
        $this->renderFile = $filename;
        return $this;
    }

    public function disableFileRendering():RenderRequest
    {
        $this->renderToFile = false;
        return $this;
    }

}