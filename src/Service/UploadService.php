<?php

namespace App\Service;

use App\Entity\MoneyOut;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadService
{
    private SluggerInterface $slugger;
    private EntityManagerInterface $entityManager;

    public function __construct(SluggerInterface $slugger, EntityManagerInterface $entityManager)
    {
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
    }

    public function validatesFile(UploadedFile $file): string
    {
        $fileName = $file->getClientOriginalName();
//        $fileExtension = $file->getClientOriginalExtension();

        $originalFileName = pathinfo($fileName, PATHINFO_FILENAME);
        $safeFileName = $this->slugger->slug($originalFileName);

        return $safeFileName.'-'.uniqid().'.'.$file->guessExtension();
    }

    public function processForm($filePathLocation): string
    {
        $explodePath = explode('/', $filePathLocation);

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
//        dd($submittedData);

        $this->saveToDatabase($submittedData);

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

    private function saveToDatabase($submittedData)
    {
//        fake user account - need to connect this to registration
        $user = new User();
        $user->setPassword('1234567');
        $user->setEmail('test911@hotmail.co.uk');
        $this->entityManager->persist($user);

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

            $moneyOutItem->setUserId($user);
            $moneyOutItem->setPaymentType($dataRowUpdated['payment_type']);
            $moneyOutItem->setAmount($amountValidated);
            $moneyOutItem->setBankAccountType($dataRowUpdated['type_of_bank_account']);
            $moneyOutItem->setDescription($dataRowUpdated['description']);

            $this->entityManager->persist($moneyOutItem);
            $this->entityManager->flush();
        }
    }

    private function checkPaymentTypes($dataRow)
    {
        $paymentTypes =
            ['Care Fees', 'Clothes', 'Broadband', 'Council Tax',
            'Electricity', 'Food', 'Rent', 'Medical Expenses',
            'Mortgage', 'Personal Allowance', 'Water', 'Wifi'];

        if (in_array($dataRow['payment_type'], $paymentTypes)) {
            return $dataRow;
        }
    }

    private function validateAmount($dataRow)
    {
        $amount = $dataRow['amount'];
//        if float drop decimal and convert to pennies, if not x 100
        return is_float($amount) ?
         (int) round((float) $amount * 100) : $amount * 100;

        // need to throw an error if theres letters
    }
}
