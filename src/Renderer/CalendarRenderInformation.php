<?php

namespace App\Renderer;

class CalendarRenderInformation
{
    /** @var float */
    private $top;

    /** @var float */
    private $left;

    /** @var float */
    private $headerHeight;

    /** @var float */
    private $columnWidth;

    /** @var float */
    private $rowHeight;

    /**
     * @return float
     */
    public function getTop(): float
    {
        return $this->top;
    }

    /**
     * @param float $top
     * @return CalendarRenderInformation
     */
    public function setTop(float $top): CalendarRenderInformation
    {
        $this->top = $top;
        return $this;
    }

    /**
     * @return float
     */
    public function getLeft(): float
    {
        return $this->left;
    }

    /**
     * @param float $left
     * @return CalendarRenderInformation
     */
    public function setLeft(float $left): CalendarRenderInformation
    {
        $this->left = $left;
        return $this;
    }

    /**
     * @return float
     */
    public function getHeaderHeight(): float
    {
        return $this->headerHeight;
    }

    /**
     * @param float $headerHeight
     * @return CalendarRenderInformation
     */
    public function setHeaderHeight(float $headerHeight): CalendarRenderInformation
    {
        $this->headerHeight = $headerHeight;
        return $this;
    }

    /**
     * @return float
     */
    public function getColumnWidth(): float
    {
        return $this->columnWidth;
    }

    /**
     * @param float $columnWidth
     * @return CalendarRenderInformation
     */
    public function setColumnWidth(float $columnWidth): CalendarRenderInformation
    {
        $this->columnWidth = $columnWidth;
        return $this;
    }

    /**
     * @return float
     */
    public function getRowHeight(): float
    {
        return $this->rowHeight;
    }

    /**
     * @param float $rowHeight
     * @return CalendarRenderInformation
     */
    public function setRowHeight(float $rowHeight): CalendarRenderInformation
    {
        $this->rowHeight = $rowHeight;
        return $this;
    }
}