<?php

namespace App\Entity;

use App\Repository\MoneyOutCategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'category_type', targetEntity: MoneyOut::class)]
    private Collection $categoryTypes;

    public function __construct()
    {
        $this->categoryTypes = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, MoneyOut>
     */
    public function getPaymentCategories(): Collection
    {
        return $this->categoryTypes;
    }

    public function addPaymentCategory(MoneyOut $categoryTypes): self
    {
        if (!$this->categoryTypes->contains($categoryTypes)) {
            $this->categoryTypes->add($categoryTypes);
            $categoryTypes->setPaymentCategory($this);
        }

        return $this;
    }

    public function removePaymentCategory(MoneyOut $categoryTypes): self
    {
        if ($this->categoryTypes->removeElement($categoryTypes)) {
            // set the owning side to null (unless already changed)
            if ($categoryTypes->getPaymentCategory() === $this) {
                $categoryTypes->setPaymentCategory(null);
            }
        }

        return $this;
    }
}
