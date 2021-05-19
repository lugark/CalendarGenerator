<?php

namespace App\Serializer\Normalizer;

use App\Calendar\Event;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class EventNormalizer implements DenormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if (!array_key_exists('name', $data)) {
            return null;
        }

        $eventType = isset($context['eventType']) ? $context['eventType'] : '';
        $entity = new Event($eventType);
        if (isset($data['date']) && !isset($data['start'])) {
            $entity->setStart($this->serializer->denormalize($data['date'], \DateTime::class));
        }

        if (isset($data['start'])) {
            $entity->setStart($this->serializer->denormalize($data['start'], \DateTime::class));
        }
        if (isset($data['end'])) {
            $entity->setEnd($this->serializer->denormalize($data['end'], \DateTime::class));
        }

        $entity->setText($data['name']);

        return $entity;
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return $type === Event::class;
    }
}