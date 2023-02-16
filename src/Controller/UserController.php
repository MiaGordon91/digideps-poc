<?php

namespace App\Controller;

use App\Form\SpreadsheetUploadFormType;
use App\Service\SpreadsheetBuilder;
use App\Service\UploadService;
use PhpOffice\PhpSpreadsheet\Writer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private SpreadsheetBuilder $spreadsheetBuilder;
    private UploadService $uploadService;
    private Security $security;

    public function __construct(
        SpreadsheetBuilder $spreadsheetBuilder,
        UploadService $uploadService,
        Security $security
    ) {
        $this->spreadsheetBuilder = $spreadsheetBuilder;
        $this->uploadService = $uploadService;
        $this->security = $security;
    }

    #[Route('/money_out', name: 'money_out')]
    public function moneyOut(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $loggedInUser = $this->security->getUser();

        $form = $this->createForm(SpreadsheetUploadFormType::class, null, [
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();

            // spreadsheet must be processed only when a file is uploaded
            $newFileName = $this->uploadService->validatesFile($file);

            // Move the file to the directory where spreadsheets are stored
            $file->move($this->getParameter('upload_directory'), $newFileName);

            // saved file is then retrieved and processed
            $filePath = $this->getParameter('kernel.project_dir').'/public/uploads/%s';

            $filePathLocation = sprintf($filePath, $newFileName);

            $this->uploadService->processForm($filePathLocation, $loggedInUser);

            // delete file from the uploads folder once data is persisted to the database
            $filesystem = new \Symfony\Component\Filesystem\Filesystem();
            $filesystem->remove($filePathLocation);

            $this->addFlash('success', 'File has successfully uploaded');

            return $this->redirectToRoute('money_out');
        }

        return $this->render('moneyOut.html.twig', [
            'title' => 'Money out',
            'uploadForm' => $form->createView(),
        ]);
    }

    #[Route('/download_spreadsheet', name: 'generate_money_out_spreadsheet')]
    public function generatingSpreadsheet(): Response
    {
        $spreadsheet = $this->spreadsheetBuilder->generateSpreadsheet();

        $writer = new Writer\Xls($spreadsheet);

        $response = new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'money_out_template.xlsx'
        );

        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}
