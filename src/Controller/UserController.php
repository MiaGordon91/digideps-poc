<?php

namespace App\Controller;

use App\Form\SpreadsheetUploadFormType;
use App\Service\SpreadsheetBuilder;
use App\Service\UploadService;
use PhpOffice\PhpSpreadsheet\Writer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private SpreadsheetBuilder $spreadsheetBuilder;
    private UploadService $uploadService;
    protected ParameterBagInterface $parameterBag;
    private UploadedFile $uploadedFile;

    private $fileLoader;

    public function __construct(
        SpreadsheetBuilder $spreadsheetBuilder,
        UploadService $uploadService,
        ParameterBagInterface $parameterBag
    ) {
        $this->spreadsheetBuilder = $spreadsheetBuilder;
        $this->uploadService = $uploadService;
        $this->parameterBag = $parameterBag;
    }

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('index.html.twig', [
            'title' => 'Complete the deputy report',
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/money_out', name: 'money_out')]
    public function moneyOut(Request $request): Response
    {
        $form = $this->createForm(SpreadsheetUploadFormType::class, null, [
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $file = $form->get('file')->getData();

            // spreadsheet must be processed only when a file is uploaded
            if ($file) {
                $newFileName = $this->uploadService->fileUploader($file);

                // Move the file to the directory where spreadsheets are stored
                try {
                    $file->move($this->getParameter('upload_directory'),
                        $newFileName
                    );
                } catch (\Throwable $e) {
//                    return null;
                }
            } else {
                throw new \Exception('Please upload a file');
            }

            // $processData = $this->uploadService->processForm($fileName);

            // persist data once it's been checked
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
