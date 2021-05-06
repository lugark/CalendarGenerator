<?php

namespace App\Renderer;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\TimePeriod;

class CalendarRenderInformation
{
    private float $top;

    private float $left;

    private float $headerHeight;

    private float $columnWidth;

    private float $rowHeight;

    private TimePeriod $timePeriod;

    private bool $crossYear;
    
    public function getTop(): float
    {
        return $this->top;
    }

    public function setTop(float $top): CalendarRenderInformation
    {
        $this->top = $top;
        return $this;
    }
    public function getLeft(): float
    {
        return $this->left;
    }

    public function setLeft(float $left): CalendarRenderInformation
    {
        $this->left = $left;
        return $this;
    }

    public function getHeaderHeight(): float
    {
        return $this->headerHeight;
    }

    public function setHeaderHeight(float $headerHeight): CalendarRenderInformation
    {
        $this->headerHeight = $headerHeight;
        return $this;
    }

    public function getColumnWidth(): float
    {
        return $this->columnWidth;
    }

    public function setColumnWidth(float $columnWidth): CalendarRenderInformation
    {
        $this->columnWidth = $columnWidth;
        return $this;
    }

    public function getRowHeight(): float
    {
        return $this->rowHeight;
    }

    public function setRowHeight(float $rowHeight): CalendarRenderInformation
    {
        $this->rowHeight = $rowHeight;
        return $this;
    }

    public function getCalendarStartsAt(): DateTime
    {
        return $this->timePeriod->start();
    }

    public function getCalendarEndsAt(): DateTime
    {
        return $this->timePeriod->end();
    }

    public function setCalendarPeriod($timePeriod): CalendarRenderInformation
    {
        $this->timePeriod = $timePeriod;
        $this->crossYear = $timePeriod->start()->year()->number() != $timePeriod->year()->number();

        return $this;
    }

    public function doesCrossYear():bool
    {
        return $this->crossYear;
    }
}