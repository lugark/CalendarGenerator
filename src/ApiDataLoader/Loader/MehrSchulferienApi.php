<?php

namespace App\ApiDataLoader\Loader;

use App\ApiDataLoader\Loader\MehrSchulferien\Locations;
use App\ApiDataLoader\Loader\MehrSchulferien\Periods;
use App\ApiDataLoader\Loader\MehrSchulferien\Types;
use App\ApiDataLoader\Transformer\MehrSchulferien;
use App\ApiDataLoader\Transformer\TransformerInterface;

class MehrSchulferienApi implements LoaderInterface
{
    const LOADER_TYPE = 'mehr_schulferien';

    protected Periods $periodsApi;
    protected Locations $locationsApi;
    protected Types $typesApi;

    public function __construct(Periods $periods, Locations $locations, Types $types)
    {
        $this->locationsApi = $locations;
        $this->periodsApi = $periods;
        $this->typesApi = $types;
    }

    public function fetchData(string $year): Response
    {
        $response = $this->periodsApi->getAllPeriods();
        if (!$response->isSuccess()) {
            return $response;
        }

        $data = array_filter(
            $response->getData()['data'],
            function($period) use ($year) {
                return (strpos($period['starts_on'], $year) !== false) || (strpos($period['ends_on'], $year) !== false);
            }
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
