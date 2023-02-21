<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataController extends AbstractController
{
    #[Route('/money_out_summary', name: 'money_out_summary')]
    public function data(): Response
    {
        return $this->render('moneyOutSummary.html.twig', [
            'title' => 'Money Out Payment Summary',
        ]);
    }
}
