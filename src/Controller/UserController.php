<?php

namespace App\Controller;

use App\Service\CsvBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(
        private CsvBuilder $csvBuilder
    ) {
    }

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
            'link' => 'Test',
        ]);
    }

    #[Route('/download_csv', name: 'generate_money_out_csv')]
    public function generatingCsv(): Response
    {
        $csv = $this->csvBuilder->generateCsv();

        return new Response($csv);
    }
}
