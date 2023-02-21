<?php

namespace App\Controller;

use App\Repository\MoneyOutRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataController extends AbstractController
{
    public function __construct(
       private Security $security,
    ) {
    }

    #[Route('/money_out_summary', name: 'money_out_summary')]
    public function data(MoneyOutRepository $moneyOutRepository, UserRepository $itemRepository): Response
    {
        $loggedInUsersEmail = $this->security->getUser()->getUserIdentifier();
        $deputyId = $itemRepository->findDeputyId($loggedInUsersEmail);

        $moneyOutPayments = $moneyOutRepository->findPaymentItemsByDeputyId($deputyId);

        return $this->render('moneyOutSummary.html.twig', [
            'title' => 'Money Out Payment Summary',
            'moneyOutPayments' => $moneyOutPayments,
        ]);
    }
}
