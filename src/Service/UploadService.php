<?php

namespace App\Service;

use App\Entity\MoneyOut;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadService
{
    public function __construct(
        private SluggerInterface $slugger,
        private EntityManagerInterface $entityManager,
        private DateTimeClass $dateTimeClass
    ) {
    }

    public function validatesFile(UploadedFile $file): string
    {
        $fileName = $file->getClientOriginalName();

        $originalFileName = pathinfo($fileName, PATHINFO_FILENAME);

        $safeFileName = $this->slugger->slug($originalFileName);

        return $safeFileName.'.'.$file->guessExtension();
    }

    public function processForm($filePathLocation, $loggedInUser): string
    {
        /*  Identify the type of $inputFileName  * */
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($filePathLocation);

        /*  Create a new Reader of the type defined in $inputFileType  * */
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

        /*  Load $inputFileName to a Spreadsheet Object  * */
        $spreadsheet = $reader->load($filePathLocation);

        /*  Convert Spreadsheet Object to an Array for ease of use  * */
        $dataArray = $spreadsheet->getActiveSheet()->toArray();

        $submittedData = $this->removesNullValues($dataArray);

        /* Removes the header columns */
        unset($submittedData[0]);

        $this->saveToDatabase($submittedData, $loggedInUser);

        return '';
    }

    private function removesNullValues($dataArray): array
    {
        /* Removes any null values */
        foreach ($dataArray as $key => &$row) {
            $row = array_filter($row,
                function ($cell) {
                    return !is_null($cell);
                }
            );
            if (0 == count($row)) {
                unset($dataArray[$key]);
            }
        }
        unset($row);

        return $dataArray;
    }

    private function saveToDatabase($submittedData, $loggedInUser)
    {
        foreach ($submittedData as $array) {
            $moneyOutItem = new MoneyOut();

            $dataRow = [
                'payment_type' => $array[0],
                'amount' => $array[1],
                'type_of_bank_account' => $array[2],
                'description' => isset($array[3]) ? $array[3] : null,
            ];

            $dataRowUpdated = $this->checkPaymentTypes($dataRow);
            $amountValidated = $this->validateAmount($dataRowUpdated);
            $paymentCategory = $this->addCategoryType($dataRowUpdated);

            $moneyOutItem->setUserId($loggedInUser);
            $moneyOutItem->setPaymentType($dataRowUpdated['payment_type']);
            $moneyOutItem->setAmount($amountValidated);
            $moneyOutItem->setBankAccountType($dataRowUpdated['type_of_bank_account']);
            $moneyOutItem->setDescription($dataRowUpdated['description']);
            $moneyOutItem->setCategory($paymentCategory);
            $moneyOutItem->setReportYear($this->dateTimeClass->now());

            $this->entityManager->persist($moneyOutItem);
            $this->entityManager->flush();
        }
    }

    private function checkPaymentTypes($dataRow)
    {
        $paymentTypes =
            ['Care Fees', 'Clothes', 'Broadband', 'Council Tax', 'Electricity', 'Food', 'Rent', 'Medical Expenses',
            'Mortgage', 'Personal Allowance', 'Water', 'Wifi'];

        if (in_array($dataRow['payment_type'], $paymentTypes)) {
            return $dataRow;
        }
    }

    private function validateAmount($dataRow): int
    {
        $amount = $dataRow['amount'];

//        if float, drop decimal and convert to pennies, if not float x 100
        return is_float($amount) ? (int) round((float) $amount * 100) : $amount * 100;
    }

    private function addCategoryType($dataRowUpdated): string
    {
        $paymentTypeSelected = $dataRowUpdated['payment_type'];

        $paymentTypeAndCategories = [
            'Care Fees' => 'Care or medical bill',
            'Clothes' => 'Personal expenses',
            'Broadband' => 'Household bills and expenses',
            'Council Tax' => 'Household bills and expenses',
            'Electricity' => 'Household bills and expenses',
            'Food' => 'Household bills and expenses',
            'Rent' => 'Accommodation costs',
            'Medical Expenses' => 'Care or medical bill',
            'Mortgage' => 'Accommodation costs',
            'Personal Allowance' => 'Personal expenses',
            'Water' => 'Household bills and expenses',
            'Wifi' => 'Household bills and expenses',
        ];

        foreach ($paymentTypeAndCategories as $paymentType => $categoryType) {
            if ($paymentType == $paymentTypeSelected) {
                return $categoryType;
            }
        }

        return '';
    }
}
