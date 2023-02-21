<?php

namespace App\Controller;

use App\Entity\MoneyOut;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataController extends AbstractController
{
    public function __construct(
       private ManagerRegistry $doctrine,
       private Security $security,
    ) {
    }

    #[Route('/money_out_summary', name: 'money_out_summary')]
    public function data(): Response
    {
        $loggedInUsersEmail = $this->security->getUser()->getUserIdentifier();
        $deputyId = $this->doctrine->getRepository(User::class)->findDeputyId($loggedInUsersEmail);

        $moneyOutPayments = $this->doctrine->getRepository(MoneyOut::class)->findPaymentItemsByDeputyId($deputyId);

        return $this->render('moneyOutSummary.html.twig', [
            'title' => 'Money Out Payment Summary',
            'moneyOutPayments' => $moneyOutPayments,
        ]);
    }
}
