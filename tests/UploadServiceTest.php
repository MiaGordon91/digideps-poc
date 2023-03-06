<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\User;
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
    private $uploadService;
    private $deputyUser;
    private $fileValidation;

    private ObjectProphecy $entityManager;
    private ObjectProphecy $slugger;

    protected function setUp(): void
    {
        $deputyUser = new User();
        $deputyUser->setEmail('test100@hotmail.co.uk');
        $deputyUser->setPassword('1234567');

        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->slugger = $this->prophesize(SluggerInterface::class);
    }

    public function testFileValidation()
    {
//        define what the expected behaviour of the mock is first
        $this->slugger->slug('money_out_template')->willReturn(new UnicodeString('money-out-template'));

        $this->uploadService = new UploadService($this->slugger->reveal(), $this->entityManager->reveal());

        $uploadedFile = new UploadedFile(
            __DIR__.'/fixtures/money_out_template.xlsx',
            'money_out_template.xlsx'
        );

        $fileValidation = $this->uploadService->validatesFile($uploadedFile);

        $this->assertEquals('money-out-template.xlsx', $fileValidation);
    }

//    public function testFormProcessesAsExpected()
//    {
//        $uploadedFile = new UploadedFile(
//            __DIR__. '/fixtures/money_out_template.xlsx',
//            'money_out_template.xlsx'
//        );
//
//        $filePathLocation = sprintf($uploadedFile['path'], $this->fileValidation);
//
//        $formProcessing = $this->uploadService->processForm($filePathLocation,$this->deputyUser->getEmail());

//        $this->assertEquals();
}

//        $arrayOfData = [
//            ['Payment Type', 'Amount', 'Type of Bank Account (e.g. Current, Packaged, Savings)', 'Description (if required)'],
//            ['Clothes',50, 'Savings','Test'],
//            ['Broadband',100,'Current'],
//            ['Rent',20000,'Savings','Moved home'],
//            ['Medical Expenses',1700,'Current']
//            ];
//
