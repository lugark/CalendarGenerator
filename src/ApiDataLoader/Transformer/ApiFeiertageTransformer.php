<?php

namespace App\ApiDataLoader\Transformer;

use App\ApiDataLoader\Loader\ApiFeiertage as ApiFeiertageLoader;
use App\ApiDataLoader\Loader\Response;

class ApiFeiertageTransformer implements TransformerInterface
{
    public function __invoke(Response $response): mixed
    {
        $data = $response->getData();
        if (! $response->isSuccess() || ! isset($data['feiertage'])) {
            return [];
        }

        $result = [];
        foreach ($data['feiertage'] as $holiday) {
            $regions = [];
            $dataSet = [
                'name' => $holiday['fname'],
                'date' => $holiday['date'],
            ];
            $regionList = array_diff(array_keys($holiday), ['fname', 'date', 'all_states', 'comment']);
            foreach ($regionList as $region) {
                if ($holiday[$region] === "1") {
                    $regions[] = strtoupper($region);
                }
            }
            $dataSet['regions'] = $regions;
            $result[] = $dataSet;
        }
        return $result;
    }

    public function getType(): string
    {
        return ApiFeiertageLoader::LOADER_TYPE;
    }
}
