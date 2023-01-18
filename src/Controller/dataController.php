<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class dataController extends AbstractController
{
    #[Route('/dataOverview', name: 'app_user')]
    public function data(): Response
    {
        return $this->render('dataVisualisation.html.twig', [
            'title' => 'Digideps App'
        ]);
    }

}