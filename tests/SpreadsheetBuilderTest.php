<?php

declare(strict_types=1);

namespace App\Tests;

use App\Service\SpreadsheetBuilder;
use PHPUnit\Framework\TestCase;

class SpreadsheetBuilderTest extends TestCase
{
    public function testFileHeaders(): void
    {
        $spreadsheetBuilder = new SpreadsheetBuilder();

        $spreadsheet = $spreadsheetBuilder->generateSpreadsheet();

        $firstHeader = $spreadsheet->getActiveSheet()->getCell('A2');
        $secondHeader = $spreadsheet->getActiveSheet()->getCell('B2');
        $thirdHeader = $spreadsheet->getActiveSheet()->getCell('C2');
        $fourthHeader = $spreadsheet->getActiveSheet()->getCell('D2');

        $this->assertEquals('Payment Type', $firstHeader);
        $this->assertEquals('Amount', $secondHeader);
        $this->assertEquals('Type of Bank Account (e.g. Current, Packaged, Savings)', $thirdHeader);
        $this->assertEquals('Description (if required)', $fourthHeader);
    }
}
