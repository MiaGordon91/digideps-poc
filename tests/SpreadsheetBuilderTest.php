<?php

declare(strict_types=1);

namespace App\Tests;

use App\Service\SpreadsheetBuilder;
use PHPUnit\Framework\TestCase;

class SpreadsheetBuilderTest extends TestCase
{
    private $spreadsheetBuilder;

    protected function setup(): void
    {
        $spreadsheetBuilder = new SpreadsheetBuilder();
        $this->spreadsheetBuilder = $spreadsheetBuilder->generateSpreadsheet()->getActiveSheet();
    }

    public function testFileHeaders(): void
    {
        $firstHeader = $this->spreadsheetBuilder->getCell('A2');
        $secondHeader = $this->spreadsheetBuilder->getCell('B2');
        $thirdHeader = $this->spreadsheetBuilder->getCell('C2');
        $fourthHeader = $this->spreadsheetBuilder->getCell('D2');

        $this->assertEquals('Payment Type', $firstHeader);
        $this->assertEquals('Amount', $secondHeader);
        $this->assertEquals('Type of Bank Account (e.g. Current, Packaged, Savings)', $thirdHeader);
        $this->assertEquals('Description (if required)', $fourthHeader);
    }

    public function testPaymentTypeDropdownMenu()
    {
        $columnType = $this->spreadsheetBuilder->getDataValidation('A3:A50')->getType();

        $this->assertEquals(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST, $columnType);
    }

    public function testPaymentTypeDropdownMenuOptions()
    {
        $dropDownOptionsPresent = $this->spreadsheetBuilder->getDataValidation('A3:A50')->getFormula1();

        $dropDownOptions = '"Care Fees, Clothes, Broadband, Council Tax, Electricity, Food, Rent, Medical Expenses, Mortgage, Personal Allowance, Water, Wifi"';

        $this->assertEquals($dropDownOptions, $dropDownOptionsPresent);
    }
}
