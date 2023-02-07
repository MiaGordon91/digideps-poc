<?php

namespace App\Entity;

use App\Repository\MoneyOutRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoneyOutRepository::class)]
class MoneyOut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $bank_account_type = null;

    #[ORM\Column(length: 255)]
    private ?string $payment_type = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'moneyOutPayments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $deputy_user = null;

    #[ORM\ManyToOne(inversedBy: '$categoryTypes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?moneyOutCategories $category_type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBankAccountType(): ?string
    {
        return $this->bank_account_type;
    }

    public function setBankAccountType(string $bank_account_type): self
    {
        $this->bank_account_type = $bank_account_type;

        return $this;
    }

    public function getPaymentType(): ?string
    {
        return $this->payment_type;
    }

    public function setPaymentType(string $payment_type): self
    {
        $this->payment_type = $payment_type;

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

    public function getUserId(): ?user
    {
        return $this->deputy_user;
    }

    public function setUserId(?user $deputy_user): self
    {
        $this->deputy_user = $deputy_user;

        return $this;
    }

    public function getPaymentCategory(): ?moneyOutCategories
    {
        return $this->category_type;
    }

    public function setPaymentCategory(?moneyOutCategories $category_type): self
    {
        $this->category_type = $category_type;

        return $this;
    }
}
