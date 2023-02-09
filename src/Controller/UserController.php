<?php

namespace App\Controller;

use App\Service\CsvBuilder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
//    public function __construct(CsvBuilder $csvBuilder)
//    {
//        $this->csvBuilder = $csvBuilder;
//    }

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('index.html.twig', [
            'title' => 'Complete the deputy report',
        ]);
    }

    #[Route('/money_out', name: 'money_out')]
    public function moneyOut(): Response
    {
        return $this->render('moneyOut.html.twig', [
            'title' => 'Money out',
        ]);
    }

    #[Route('/download_csv', name: 'generate_money_out_csv')]
    public function generatingCsv()
    {
//        $spreadsheet = $this->csvBuilder->generateCsv();
//
//        header('Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//        header('Content-Disposition:attachment;filename="money_out_template.xlsx"');
//
//        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
//        $writer->save('php://output');

//        $response = new Response($spreadsheet);
//        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//        $disposition = $response->headers->makeDisposition(
//            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
//            'money_out_template.xlsx'
//        );
//
//        $response->headers->set('Content-Disposition', $disposition);

//        return $writer;
    }
}
