<?php

namespace App\Service;

use Symfony\Component\DomCrawler\Crawler;

class ApiCrawler
{
    const DEUTSCHE_FEIERTAGE_URL = 'https://deutsche-feiertage-api.de/api/v1/';
    const SCHULFERIEN_ORG_URL = 'https://www.schulferien.org/deutschland/ferien/';

    /** @var FederalService  */
    private $federalService;

    public function __construct(FederalService $federalService)
    {
        $this->federalService = $federalService;
    }

    public function fetchFromDFAPI(string $year): array
    {
        $ch = curl_init(self::DEUTSCHE_FEIERTAGE_URL . $year);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'X-DFA-Token: dfa')
        );

        $result = curl_exec($ch);
        $data = json_decode($result, true);
        if ($data['result']) {
            foreach ($data['holidays'] as $key => $holiday) {
                $regions = [];
                foreach ($holiday['holiday']['regions'] as $region => $hasHoliday) {
                    $region = $region === 'bay' ? 'BY' : strtoupper($region);
                    if ($hasHoliday) {
                        $regions[] = $region;
                    }
                }
                $data['holidays'][$key]['holiday']['regions'] = $regions;
            }
            return $data['holidays'];
        }

        return [];
    }

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