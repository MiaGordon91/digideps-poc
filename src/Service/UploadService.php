<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadService
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
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
        unset($dataArray[0]);

        return;
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
}
