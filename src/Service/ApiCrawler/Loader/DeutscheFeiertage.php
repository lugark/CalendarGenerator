<?php

namespace App\Service\ApiCrawler\Loader;

use App\Service\ApiCrawler\LoaderInterface;

class DeutscheFeiertage implements LoaderInterface
{
    const DEUTSCHE_FEIERTAGE_URL = 'https://deutsche-feiertage-api.de/api/v1/';

    public function fetch()
    {
    }

    public function getType()
    {
    }
}