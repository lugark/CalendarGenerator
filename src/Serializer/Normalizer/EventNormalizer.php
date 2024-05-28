<?php

namespace App\Serializer\Normalizer;

use Calendar\Pdf\Renderer\Event\Event;
use DateTime;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class EventNormalizer implements DenormalizerInterface
{
    private readonly DateTimeNormalizer $dateTimeNormalizer;

    public function __construct()
    {
        $this->dateTimeNormalizer = new DateTimeNormalizer();
    }

    public function denormalize($data, string $type, string|null $format = null, array $context = []): mixed
    {
        if (!array_key_exists('name', $data)) {
            return null;
        }

        $eventType = $context['eventType'] ?? '';
        $entity = new Event($eventType);
        $entity->setText($data['name']);

        if (isset($data['date']) && !isset($data['start'])) {
            $date = ($this->dateTimeNormalizer->denormalize($data['date'], DateTime::class));
            $entity->setEventPeriod($date, $date);
            return $entity;
        }

        if (isset($data['start'])) {
            if (!isset($data['end'])) {
                $data['end'] = $data['start'];
            }
            $start = $this->dateTimeNormalizer->denormalize($data['start'], DateTime::class);
            $end = $this->dateTimeNormalizer->denormalize($data['end'], DateTime::class);

            $entity->setEventPeriod($start, $end);
        }

        return $entity;
    }

    public function supportsDenormalization($data, string $type, string|null $format = null): bool
    {
        return $type === Event::class;
    }
}
