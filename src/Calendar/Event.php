<?php

namespace App\Calendar;

use App\Calendar\Event\Types;

class Event
{
    /** @var \DateTime */
    private $start;

    /** @var \DateTime */
    private $end;

    private $text;

    /** @var string */
    private $type;

    /** @var array */
    private $additionalInformation;

    public function __construct($type=Types::EVENT_TYPE_CUSTOM)
    {
        $this->type = $type;
    }

    public function getStart(): \DateTime
    {
        return $this->start;
    }

    public function setStart(\DateTime $start): Event
    {
        $this->start = $start;
        return $this;
    }

    public function getEnd(): ?\DateTime
    {
        return $this->end;
    }

    public function setEnd(\DateTime $end): Event
    {
        $this->end = $end;
        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText($text): Event
    {
        $this->text = $text;
        return $this;
    }

    public function getAdditionalInformation(): array
    {
        return $this->additionalInformation;
    }

    public function setAdditionalInformation(array $info): Event
    {
        $this->additionalInformation = $info;
        return $this;
    }

    public function isInRange(\DateTime $start, \DateTime $end): bool
    {
        if (empty($this->end)) {
            return ($this->start >= $start);
        }

        return ($this->start >= $start) && ($this->end <= $end);
    }

    public function getType()
    {
        return $this->type;
    }
}