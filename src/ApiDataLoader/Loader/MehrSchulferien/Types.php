<?php

namespace App\ApiDataLoader\Loader\MehrSchulferien;

class Types extends AbstractApi
{
    public const TYPES_PATH = 'holiday_or_vacation_types';

    /**
     * @var array<mixed>
     */
    protected array $types = [];

    public function getApiSubPath(): string
    {
        return self::TYPES_PATH;
    }

    protected function loadTypes(): void
    {
        $response = $this->curlRequest->execute(
            $this->getApiUrl(),
            [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                ],
            ]
        );

        $this->types = array_column($response->getData()['data'], null, 'id');
    }

    /**
     * @return array<mixed>
     */
    public function getType(int $id): array
    {
        if (empty($this->types)) {
            $this->loadTypes();
        }

        return $this->types[$id];
    }
}
