<?php

namespace App\Service;

class FederalService
{

    private $federalMapping = array(
        ['BB','Brandenburg','Potsdam '],
        ['BE','Berlin','Berlin '],
        ['BW','Baden-Württemberg','Stuttgart '],
        ['BY','Bayern','München '],
        ['HB','Bremen','Bremen '],
        ['HE','Hessen','Wiesbaden '],
        ['HH','Hamburg','Hamburg '],
        ['MV','Mecklenburg-Vorpommern','Schwerin '],
        ['NI','Niedersachsen','Hannover '],
        ['NW','Nordrhein-Westfalen','Düsseldorf '],
        ['RP','Rheinland-Pfalz','Mainz '],
        ['SH','Schleswig-Holstein','Kiel '],
        ['SL','Saarland','Saarbrücken '],
        ['SN','Sachsen','Dresden '],
        ['ST','Sachsen-Anhalt','Magdeburg '],
        ['TH','Thüringen','Erfurt']);

    public function getAbbrevationByFullName($name) :string
    {
        $result = array_filter($this->federalMapping, function ($row) use ($name) {
               return $name === $row[1];
            });
        $result = array_shift($result);

        return !empty($result) ? $result[0] : '';
    }

}