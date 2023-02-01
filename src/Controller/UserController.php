<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('index.html.twig', [
            'title' => 'Homepage',
        ]);
    }

    #[Route('/money_out', name: 'money_out')]
    public function moneyOut(): Response
    {
        return $this->render('moneyOut.html.twig', [
            'title' => 'Money out',
        ]);
    }

//    /**
//     * @return array
//     * @Route( "/test" , name: "testmethod")
//     */
//    public function testMethod(): array
//    {
//       return [];
//    }
}
