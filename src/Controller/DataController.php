<?php

namespace App\Controller;

use App\Repository\MoneyOutRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception;
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
    public function data(MoneyOutRepository $moneyOutRepository, UserRepository $userRepository): Response
    {
        $loggedInUsersEmail = $this->security->getUser()->getUserIdentifier();
        $deputyId = $userRepository->findDeputyId($loggedInUsersEmail);

        $clientInformation = $userRepository->loadClientByIdentifier($loggedInUsersEmail);

        $moneyOutPayments = $moneyOutRepository->findPaymentItemsByDeputyId(implode('', $deputyId[0]));

        return $this->render('moneyOutSummary.html.twig', [
            'title' => 'Money Out Payment Summary',
            'moneyOutPayments' => $moneyOutPayments,
            'clientInformation' => $clientInformation,
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/graphSummary', name: 'app_user')]
    public function graphOverview(MoneyOutRepository $moneyOutRepository, UserRepository $userRepository): Response
    {
        $loggedInUsersEmail = $this->security->getUser()->getUserIdentifier();
        $deputyIdArray = $userRepository->findDeputyId($loggedInUsersEmail);
        $deputyId = implode('', $deputyIdArray[0]);

        $currentYearCategorySummary = $moneyOutRepository->findCategoryItemSummaryByDeputyIdForCurrentYear($deputyId);
        $previousAndCurrentCategorySummary = $moneyOutRepository->findCategoryItemSummaryByDeputyIdForCurrentAndPreviousYear($deputyId);

        foreach ($previousAndCurrentCategorySummary as &$value) {
            $value['amount'] = $value['amount'] / 100;
        }

        foreach ($currentYearCategorySummary as &$value) {
            $value['amount'] = $value['amount'] / 100;
        }

        return $this->render('dataVisualisation.html.twig', [
            'title' => 'Money Out Payment Graph Overview',
            'categorySummary' => $currentYearCategorySummary,
            'yearOnYearSummary' => $previousAndCurrentCategorySummary,
        ]);
    }
}
