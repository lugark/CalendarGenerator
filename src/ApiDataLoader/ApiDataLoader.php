<?php

namespace App\ApiDataLoader;

use Symfony\Component\DomCrawler\Crawler;
use App\Service\FederalService;
use App\ApiDataLoader\Loader\Response;
use Exception;

class ApiDataLoader
{
    /** @var FederalService  */
    private $federalService;

    /** @var <LoaderInterface></LoaderInterface> */
    private $loader;

    /** @var <TransformerInterface></TransformerInterface> */
    private $transformer;

    public function __construct(FederalService $federalService, iterable $loader, iterable $transformer)
    {
        $this->federalService = $federalService;

        foreach ($loader as $instance) {
            $this->loader[$instance->getType()] = $instance;
        }

        foreach ($transformer as $instance) {
            $this->transformer[$instance->getType()] = $instance;
        }
    }

    public function fetchData(string $type, string $year): array
    {
        if (!array_key_exists($type, $this->loader)) {
            throw new DataLoaderException('Can not find api-loader for ' . $type);
        }

        /** @var Response */
        $response = $this->loader[$type]->fetch($year);
        if (!$response->isSuccess()) {
            throw new DataLoaderException('Error loading data: ' . $response->getResponse());
        }

        if (array_key_exists($type, $this->transformer)) {
            $data = $this->transformer[$type]($response);
        } else {
            $data = $response->getData();
        }

        return $data;
    }
}