<?php

namespace App\Entity;

use App\Repository\MoneyOutCategoriesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoneyOutCategoriesRepository::class)]
class MoneyOutCategories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $payment_category = null;

    #[ORM\Column(length: 255)]
    private ?string $payment_type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentCategory(): ?string
    {
        return $this->payment_category;
    }

    public function setPaymentCategory(string $payment_category): self
    {
        $this->payment_category = $payment_category;

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
}
