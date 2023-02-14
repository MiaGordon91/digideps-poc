<?php

namespace App\Service;

use App\Entity\MoneyOut;
use http\Client\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadService
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function fileUploader(UploadedFile $file): string
    {
        $fileName = $file->getClientOriginalName();
//        $fileExtension = $file->getClientOriginalExtension();

        $originalFileName = pathinfo($fileName, PATHINFO_FILENAME);
        $safeFileName = $this->slugger->slug($originalFileName);

        return $safeFileName.'-'.uniqid().'.'.$file->guessExtension();
    }

    public function processForm($fileName): string
    {
        $XlsxFile = $fileName;

//        $inputFileType = 'xlsx';

        /**  Identify the type of $inputFileName  **/
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($XlsxFile);

        /**  Create a new Reader of the type that has been identified  **/
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

        /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($XlsxFile);

        /**  Convert Spreadsheet Object to an Array for ease of use  **/
        $schedules = $spreadsheet->getActiveSheet()->toArray();
        dd($schedules);

        /*  Create a new Reader of the type defined in $inputFileType  * */
//        $reader = IOFactory::createReader($inputFileType);
//
//        /*  Advise the Reader that we only want to load cell data  * */
//        $reader->setReadDataOnly(true);
//
//        /*  Load $inputFileName to a Spreadsheet Object  * */
//        $spreadsheet = $reader->load($fileName);
//
//        /* Load $inputFileName to a Spreadsheet Object  * */
//        $spreadsheet = IOFactory::load($fileName);
//        dd($spreadsheet);

//        $moneyOutItem = new MoneyOut();

//         method will identify selected file type and store under $file_type variable

//        dd($_FILES['spreadsheet_upload_form']['name']['file']);
//        $file_type = IOFactory::identify($fileName);
//        dd($file_type);
        // it will create reader under selected file type
//        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);

        // will load selected file
//        $spreadsheet = $reader->load($uploadedFile['spreadsheet_upload_form']['name']);

//        return '';
    }
}

// {
// //        Set up doctrine!
// //        $entityManager = $doctrine->getManager();
//    $moneyOutItem = new MoneyOut();
//
//
//    $allowed_extension = ['xlsx', 'csv', 'xls', 'numbers'];
//    $file_array = explode('.', $globalVariable['spreadsheet_upload_form']['name']);
//    $file_extension = end($file_array);
//
//    if (in_array($file_extension, $allowed_extension)) {
//        // method will identify selected file type and store under $file_type variable
//        $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($globalVariable['spreadsheet_upload_form']['name']);
//
//        // it will create reader under selected file type
//        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);
//
//        // will load selected file
//        $spreadsheet = $reader->load($globalVariable['spreadsheet_upload_form']['name']);
//
//        // Will return active spreadsheet data in array format
//        $data = $spreadsheet->getActiveSheet()->toArray();
//
//        foreach ($data as $row) {
//            $insert_data = [
//                'payment_type' => $row[0],
//                'amount' => $row[1],
//                'type_of_bank_account' => $row[2],
//                'description' => $row[3],
//            ];
//
//            // need to find a way to add in deputy id also
//            $moneyOutItem->setPaymentType($insert_data['payment_type']);
//            $moneyOutItem->setAmount($insert_data['amount']);
//            $moneyOutItem->setBankAccountType($insert_data['type_of_bank_account']);
//            $moneyOutItem->setDescription($insert_data['description']);
//        }
//
// //                        checks to see if any data is an incorrect datatype and throws error
//        $errors = $this->validator->validate($moneyOutItem);
//
//        if (count($errors) > 0) {
//            return new Response((string) $errors, 400);
//        } else {
//            $entityManager->persist($moneyOutItem);
//            $entityManager->flush();
//
//            $message = '<div class="alert alert-danger">File uploaded successfully</div>';
//        }
//    } else {
//        $message = '<div class="alert alert-danger">Only .xlsx .csv or .xls file allowed</div>';
//    }
//
//    return $message;
// }
