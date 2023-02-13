<?php

namespace App\Controller;

use App\Form\SpreadsheetUploadFormType;
use App\Service\SpreadsheetBuilder;
use PhpOffice\PhpSpreadsheet\Writer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private SpreadsheetBuilder $spreadsheetBuilder;

    public function __construct(SpreadsheetBuilder $spreadsheetBuilder)
    {
        $this->spreadsheetBuilder = $spreadsheetBuilder;
    }

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('index.html.twig', [
            'title' => 'Complete the deputy report',
        ]);
    }

    #[Route('/money_out', name: 'money_out')]
    public function moneyOut(Request $request): Response
    {
        $form = $this->createForm(SpreadsheetUploadFormType::class, null, [
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

//        add validation here
//        if($form->isSubmitted() && $form->isValid()) {
//
//        }

        return $this->render('moneyOut.html.twig', [
            'title' => 'Money out',
            'uploadForm' => $form->createView(), [],
        ]);
    }

    #[Route('/download_spreadsheet', name: 'generate_money_out_spreadsheet')]
    public function generatingSpreadsheet()
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
