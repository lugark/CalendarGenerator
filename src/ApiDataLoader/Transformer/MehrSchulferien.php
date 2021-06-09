<?php

namespace App\ApiDataLoader\Transformer;

use App\ApiDataLoader\Loader\MehrSchulferienApi;
use App\ApiDataLoader\Loader\Response;

class MehrSchulferien implements TransformerInterface
{
    /** Expected result:
    * array(6) {
    *     [0] =>
    *         array(17) {
    *             'name' =>
    *             string(12) "Winterferien"
    *             'BW' =>
    *                 array(0) {
    *                 }
    *             'BY' =>
    *                 array(2) {
    *                 'start' =>
    *                 string(10) "24.02.2020"
    *                 'end' =>
    *                 string(10) "28.02.2020"
    *                 }
    **/
    public function __invoke(Response $response)
    {
        $schoolVacation = [];
        foreach ($response->getData() as $period) {
            if (!$period['is_school_vacation']) {
                continue;
            }
            $typeId =  $period['type']['id'];
            if (!isset($schoolVacation[$typeId])) {
                $schoolVacation[$typeId] = ['name' => $period['type']['colloquial']];
            }
            $schoolVacation[$typeId][strtoupper($period['location']['code'])] = [
                'start' => $period['starts_on'],
                'end' => $period['ends_on'],
            ];
        }

        return array_values($schoolVacation);
    }

    public function getType(): string
    {
        return MehrSchulferienApi::LOADER_TYPE;
    }
}