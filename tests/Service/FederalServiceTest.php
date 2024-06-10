<?php

namespace App\Tests\Service;

use App\Service\FederalService;
use PHPUnit\Framework\TestCase;

class FederalServiceTest extends TestCase
{

    public function getFederalNames()
    {
        return [
            'SachsenAnhalt'       => ['Sachsen-Anhalt', 'ST'],
            'Sachsen'             => ['Sachsen', 'SN'],
            'TestNothingReturned' => ['Test', ''],
            'Bayern'              => ['Bayern', 'BY'],
        ];
    }

    /**
     * @dataProvider getFederalNames
     */
    public function testGetAbbrevationByFullName($longname, $abbrevation): void
    {
        $federalService = new FederalService();
        $this->assertEquals($abbrevation, $federalService->getAbbrevationByFullName($longname));
    }
}
