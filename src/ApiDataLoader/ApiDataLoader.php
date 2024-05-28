<?php

namespace App\ApiDataLoader;

use App\ApiDataLoader\Loader\LoaderInterface;
use App\ApiDataLoader\Loader\Response;

class ApiDataLoader
{
    public function __construct(
        /** @var LoaderInterface[] */
        private readonly iterable $loader
    ) {
    }

    /**
     * @return array<mixed>
     */
    public function fetchData(string $type, string $year): array
    {
        $loader = $this->getMatchingLoader($type);

        /** @var Response */
        $response = $loader->fetchData($year);
        if (!$response->isSuccess()) {
            throw new DataLoaderException('Error loading data: ' . $response->getResponse());
        }

        $transformer = $loader->getTransformer();
        if (!empty($transformer)) {
            $data = $transformer($response);
        } else {
            $data = $response->getData();
        }

        return $data;
    }

    private function getMatchingLoader(string $type): LoaderInterface
    {
        foreach ($this->loader as $loader) {
            if ($loader->getType() == $type) {
                return $loader;
            }
        }

        throw new DataLoaderException('Can not find api-loader for ' . $type);
    }
}
