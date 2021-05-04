<?php

namespace App\Service\ApiCrawler;

interface LoaderInterface
{
    public function fetch();

    public function getType();
    
}
