<?php

namespace App\Renderer;

class CalendarRenderInformation
{
    private float $top;

    private float $left;

    private float $headerHeight;

    private float $columnWidth;

    private float $rowHeight;

    private \DateTime $calendarStartsAt;

    private \DateTime $calendarEndsAt;

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

    public function getCalendarStartsAt(): \DateTime
    {
        return $this->calendarStartsAt;
    }

    public function setCalendarStartsAt(\DateTime $calendarStartsAt): CalendarRenderInformation
    {
        $this->calendarStartsAt = $calendarStartsAt;
        return $this;
    }

    public function getCalendarEndsAt(): \DateTime
    {
        return $this->calendarEndsAt;
    }

    public function setCalendarEndsAt(\DateTime $calendarEndsAt): CalendarRenderInformation
    {
        $this->calendarEndsAt = $calendarEndsAt;
        return $this;
    }
}