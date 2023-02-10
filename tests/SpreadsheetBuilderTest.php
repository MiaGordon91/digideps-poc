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

    public function testPaymentTypeDropdownMenu()
    {
        $spreadsheetBuilder = new SpreadsheetBuilder();

        $spreadsheet = $spreadsheetBuilder->generateSpreadsheet()->getActiveSheet();
        $columnType = $spreadsheet->getDataValidation('A3:A50')->getType();

        $this->assertEquals(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST, $columnType);
    }

    public function testPaymentTypeDropdownMenuOptions()
    {
        $spreadsheetBuilder = new SpreadsheetBuilder();

        $spreadsheet = $spreadsheetBuilder->generateSpreadsheet()->getActiveSheet();

        $dropDownOptionsPresent = $spreadsheet->getDataValidation('A3:A50')->getFormula1();

        $dropDownOptions = '"Care Fees, Clothes, Broadband, Council Tax, Electricity, Food, Rent, Medical Expenses, Mortgage, Personal Allowance, Water, Wifi"';

        $this->assertEquals($dropDownOptions, $dropDownOptionsPresent);
    }
}
