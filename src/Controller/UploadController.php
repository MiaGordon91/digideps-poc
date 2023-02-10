<?php

namespace App\Controller;

use App\Entity\MoneyOut;
use Doctrine\Persistence\ManagerRegistry;
use http\Client\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UploadController extends AbstractController
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

      public function moneyOutUpload(ManagerRegistry $doctrine, ValidatorInterface $validator)
      {
          $entityManager = $doctrine->getManager();
          $moneyOutItem = new MoneyOut();

            //    if this condition fails then that means the user hasn't selected a file and throws a message
          if ('' != $_FILES['import_excel']['name']) {
              $allowed_extension = ['xlsx', 'csv', 'xls'];
              $file_array = explode('.', $_FILES['import_excel']['name']);
              $file_extension = end($file_array);

              if (in_array($file_extension, $allowed_extension)) {
                  // method will identify selected file type and store under $file_type variable
                  $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($_FILES['import_excel']['name']);

                  // it will create reader under selected file type
                  $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);

                  // will load selected file
                  $spreadsheet = $reader->load($_FILES['import_excel']['tmp_name']);

                  // Will return active spreadsheet data in array format
                  $data = $spreadsheet->getActiveSheet()->toArray();

                  foreach ($data as $row) {
                      $insert_data = [
                          'payment_type' => $row[0],
                          'amount' => $row[1],
                          'type_of_bank_account' => $row[2],
                          'description' => $row[3],
                      ];

                      // need to find a way to add in deputy id also
                      $moneyOutItem->setPaymentType($insert_data['payment_type']);
                      $moneyOutItem->setAmount($insert_data['amount']);
                      $moneyOutItem->setBankAccountType($insert_data['type_of_bank_account']);
                      $moneyOutItem->setDescription($insert_data['description']);
                  }

//                        checks to see if any data is an incorrect datatype and throws error
                  $errors = $this->validator->validate($moneyOutItem);

                  if (count($errors) > 0) {
                      return new Response((string) $errors, 400);
                  } else {
                      $entityManager->persist($moneyOutItem);
                      $entityManager->flush();

                      $message = '<div class="alert alert-danger">File uploaded successfully</div>';
                  }
              } else {
                  $message = '<div class="alert alert-danger">Only .xlsx .csv or .xls file allowed</div>';
              }
          } else {
              $message = '<div class="alert alert-danger">Please select file</div>';
          }

          return $message;
      }
}
