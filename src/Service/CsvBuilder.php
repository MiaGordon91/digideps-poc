<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Protection;

class CsvBuilder
{
    public function generateCsv()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /*
         * set table title, merge and format
         */
        $sheet->setCellValue('A1', 'Money Out Payments Table');
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1:D2')->getFont()->setBold(true);
        $sheet->getStyle('A1:D1')->getFont()->setSize(18);
        $sheet->getStyle('A1:D50')
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A1:D50')
            ->getAlignment()->setWrapText(true);

        $sheet->getRowDimension('2')->setRowHeight(20);

//        Redundant column isnt removing - need to fix this!
        $sheet->removeColumn('E');

        /*
         * Set header for the 'Payment Type' column
         */
        $sheet->setCellValue('A2', 'Payment Type');
        $sheet->getColumnDimension('A')->setWidth(15);

        /**
         * Set the 'drop down list' validation on column A.
         */
        $validationDropDown = $sheet->getDataValidation('A3:A30');

        /*
         * Since the validation is for a 'drop down list',
         * set the validation type to 'List'.
         */
        $validationDropDown->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);

        /*
         * List drop down options.
         */
        $validationDropDown->setFormula1('"Care Fee, Broadband, Medical Expenses, Wifi"');

        /*
         * Do not allow empty value.
         */
        $validationDropDown->setAllowBlank(false);

        /*
         * Show drop down.
         */
        $validationDropDown->setShowDropDown(true);

        /*
         * Display a cell 'note' about the
         * 'drop down list' validation.
         */
        $validationDropDown->setShowInputMessage(true);

        /*
         * Set the 'note' title.
         */
        $validationDropDown->setPromptTitle('Note');

        /*
         * Describe the note.
         */
        $validationDropDown->setPrompt('Please select a payment type from the drop down options.');

        $validationDropDown->setShowErrorMessage(true);

        /*
         * Do not allow any other data to be entered
         * by setting the style to 'Stop'.
         */
        $validationDropDown->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);

        $validationDropDown->setErrorTitle('Invalid option');
        $validationDropDown->setError('Select one from the drop down list.');

        /*
         * Set header for the 'Amount' column
         */
        $sheet->setCellValue('B2', 'Amount');
        $sheet->getColumnDimension('B')->setWidth(15);

        /*
         * Set header for the 'Description' column
         */
        $sheet->setCellValue('C2', 'Bank Account Alias');
        $sheet->getColumnDimension('C')->setWidth(20);

        /*
         * Set header for the 'Description' column
         */
        $sheet->setCellValue('D2', 'Description (if required)');
        $sheet->getColumnDimension('D')->setWidth(50);

//        Attempting to protect title and headers - need to fix
//        $sheet->getProtection()->setSheet(true);
//        $sheet->getStyle('A3:D50')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="money_out_template.xlsx"');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
