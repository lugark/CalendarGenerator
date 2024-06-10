<?php

namespace App\ApiDataLoader\Loader;

use App\ApiDataLoader\Loader\MehrSchulferien\Locations;
use App\ApiDataLoader\Loader\MehrSchulferien\Periods;
use App\ApiDataLoader\Loader\MehrSchulferien\Types;
use App\ApiDataLoader\Transformer\MehrSchulferien;
use App\ApiDataLoader\Transformer\TransformerInterface;

class MehrSchulferienApi implements LoaderInterface
{
    public const LOADER_TYPE = 'mehr_schulferien';

    public function __construct(protected Periods $periodsApi, protected Locations $locationsApi, protected Types $typesApi)
    {
    }

    public function fetchData(string $year): Response
    {
        $response = $this->periodsApi->getAllPeriods();
        if (!$response->isSuccess()) {
            return $response;
        }

        $data = array_filter(
            $response->getData()['data'],
            fn($period) => (str_contains((string) $period['starts_on'], $year)) || (str_contains((string) $period['ends_on'], $year))
        );

        foreach ($data as $key => $period) {
            $data[$key]['type'] = $this->typesApi->getType(intval($period[Periods::TYPES_FIELD]));
            $data[$key]['location'] = $this->locationsApi->getLocation(intval($period[Periods::LOCATIONS_FIELD]));
        }

        return new Response(true, 200, 'Fetched Data!', $data);
    }

    public function getType(): string
    {
        return self::LOADER_TYPE;
    }

    public function getTransformer(): ?TransformerInterface
    {
        return new MehrSchulferien();
    }


}
