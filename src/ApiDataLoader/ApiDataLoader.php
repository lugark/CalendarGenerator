<?php

namespace App\ApiDataLoader;

use Symfony\Component\DomCrawler\Crawler;
use App\Service\FederalService;
use App\ApiDataLoader\Loader\Response;
use Exception;

class ApiDataLoader
{
    const SCHULFERIEN_ORG_URL = 'https://www.schulferien.org/deutschland/ferien/';

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

    /**
     * @deprecated
     * @codeCoverageIgnore
     */
    public function fetchDataFromSF(string  $crawlYear): array
    {
        $values = $this->crawlSFWebsite($crawlYear);
        $vacations = $values['header'];

        foreach ($values['values'] as $federalVacation) {
            $federalShort = $this->federalService->getAbbrevationByFullName($federalVacation['federal']);
            foreach ($federalVacation['vacation'] as $column => $value) {
                $vacations[$column][$federalShort] = $this->parseSFWebsiteDates($value, $crawlYear);
            }
        }
        return $vacations;
    }

    /**
     * @deprecated
     * @codeCoverageIgnore
     */
    private function parseSFWebsiteDates(string $date, string $year): array
    {
        $parsedDate = [];
        if (preg_match('/^(?<start>(?:\d{2}.){2})(?:.*(?<end>(?:\d{2}.){2}))?/', $date, $matches)) {
            $parsedDate['start'] = $matches['start'] . $year;
            $parsedDate['end'] = isset($matches['end']) ? $matches['end'] : $matches['start'];
            if ((strpos($parsedDate['start'],'.01.') == 0) && (strpos($parsedDate['end'],'.01.') != 0)){
                $parsedDate['end'] .= ($year+1);
            } else {
                $parsedDate['end'] .= $year;
            }
         }
        return $parsedDate;
    }

    /**
     * @deprecated
     * @codeCoverageIgnore
     */
    private function crawlSFWebsite(string $crawlYear): array
    {
        $ch = curl_init(self::SCHULFERIEN_ORG_URL . $crawlYear);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $htmlContent = curl_exec($ch);
        $crawler = new Crawler($htmlContent);

        $headerNames = $crawler->filterXPath('//table/thead/tr/th/div')
            ->each(function (Crawler $c) {
               return ['name' => trim($c->extract(['_text'])[0])];
            });

        $values = $crawler->filterXPath('//table/tbody/*')
            ->each(function (Crawler $c) {
                $vacation = array_map(function($content) {
                    return trim(str_replace('*', '', $content));
                }, $c->filterXPath('tr/td//div')->extract(['_text']));

                return [
                    'federal' => trim($c->filterXPath('tr/td//span[@class="sf_table_index_row_value"]')->text()),
                    'vacation' => $vacation
                ];
            });

        return ['header' => $headerNames, 'values' => $values];
    }
}