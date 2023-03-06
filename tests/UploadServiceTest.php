<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\MoneyOut;
use App\Entity\User;
use App\Service\DateTimeClass;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\UnicodeString;

class UploadServiceTest extends TestCase
{
    use ProphecyTrait;

    private UploadService $uploadService;

    private ObjectProphecy|EntityManagerInterface $entityManager;
    private ObjectProphecy|SluggerInterface $slugger;
    private ObjectProphecy|DateTimeClass $dateTime;

    protected function setUp(): void
    {
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->slugger = $this->prophesize(SluggerInterface::class);
        $this->dateTime = $this->prophesize(DateTimeClass::class);
    }

    public function testFileValidation()
    {
//        define what the expected behaviour of the mock is first
        $this->slugger->slug('money_out_template')->willReturn(new UnicodeString('money-out-template'))->shouldBeCalled();

        $this->uploadService = new UploadService(
            $this->slugger->reveal(),
            $this->entityManager->reveal(),
            $this->dateTime->reveal()
        );

        $uploadedFile = new UploadedFile(
            __DIR__.'/fixtures/money_out_template.xlsx',
            'money_out_template.xlsx'
        );

        $fileValidation = $this->uploadService->validatesFile($uploadedFile);

        $this->assertEquals('money-out-template.xlsx', $fileValidation);
    }

    public function testFormProcessesAsExpected()
    {
//        define what the mock is - testing what happens inside code instead of the return value because there isn't one
        $now = new \DateTime('2022-05-03');
        $this->dateTime->now()->willReturn($now)->shouldBeCalled();

        $loggedInUser = new User();

        $moneyOutItem1 = new MoneyOut();
        $moneyOutItem1->setUserId($loggedInUser);
        $moneyOutItem1->setPaymentType('Clothes');
        $moneyOutItem1->setAmount(5000);
        $moneyOutItem1->setBankAccountType('Savings');
        $moneyOutItem1->setDescription('Test');
        $moneyOutItem1->setCategory('Personal expenses');
        $moneyOutItem1->setReportYear($now);

        $moneyOutItem2 = new MoneyOut();
        $moneyOutItem2->setUserId($loggedInUser);
        $moneyOutItem2->setPaymentType('Broadband');
        $moneyOutItem2->setAmount(10000);
        $moneyOutItem2->setBankAccountType('Current');
        $moneyOutItem2->setDescription('');
        $moneyOutItem2->setCategory('Household bills and expenses');
        $moneyOutItem2->setReportYear($now);

        $this->entityManager->persist($moneyOutItem1)->shouldBeCalled();
        $this->entityManager->flush()->shouldBeCalled();

        $this->entityManager->persist($moneyOutItem2)->shouldBeCalled();
        $this->entityManager->flush()->shouldBeCalled();

        $this->uploadService = new UploadService(
            $this->slugger->reveal(),
            $this->entityManager->reveal(),
            $this->dateTime->reveal()
        );

        $formProcessing = $this->uploadService->processForm(__DIR__.'/fixtures/money_out_template.xlsx', $loggedInUser);
        // no assertion needed as there's no return value
    }
}
