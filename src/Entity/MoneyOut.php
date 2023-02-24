<?php

namespace App\Entity;

use App\Repository\MoneyOutRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoneyOutRepository::class)]
class MoneyOut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $bankAccountType = null;

    #[ORM\Column(length: 255)]
    private ?string $paymentType = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'moneyOutPayments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $deputyUser = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $reportYear = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBankAccountType(): ?string
    {
        return $this->bankAccountType;
    }

    public function setBankAccountType(string $bankAccountType): self
    {
        $this->bankAccountType = $bankAccountType;

        return $this;
    }

    public function getPaymentType(): ?string
    {
        return $this->paymentType;
    }

    public function setPaymentType(string $paymentType): self
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->deputyUser;
    }

    public function setUserId(?User $deputyUser): self
    {
        $this->deputyUser = $deputyUser;

        return $this;
    }

    public function getReportYear(): ?\DateTimeInterface
    {
        return $this->reportYear;
    }

    public function setReportYear(?\DateTimeInterface $reportYear): self
    {
        $this->reportYear = $reportYear;

        return $this;
    }
}
