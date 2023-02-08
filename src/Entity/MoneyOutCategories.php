<?php

namespace App\Entity;

use App\Repository\MoneyOutCategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoneyOutCategoriesRepository::class)]
class MoneyOutCategories
{
    public static function getPaymentCategories(): array
    {
        return [
                'Care Fees' => 'Care or medical bill',
                'Clothes' => 'Personal expenses',
                'Broadband' => 'Household bills and expenses',
                'Council Tax' => 'Household bills and expenses',
                'Electricity' => 'Household bills and expenses',
                'Food' => 'Household bills and expenses',
                'Rent' => 'Accommodation costs',
                'Medical Expenses' => 'Care or medical bill',
                'Mortgage' => 'Accommodation costs',
                'Personal Allowance' => 'Personal expenses',
                'Water' => 'Household bills and expenses',
                'Wifi' => 'Household bills and expenses',
            ];
    }

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

    public function __construct(string $payment_category, string $payment_type)
    {
//        $this->categoryTypes = new ArrayCollection();
        $this->payment_category = $payment_category;
        $this->payment_type = $payment_type;
    }

    public function getMoneyOutCategory()
    {
        foreach (self::getPaymentCategories() as $paymentCategory => $paymentType) {
            if ($paymentCategory == $this->payment_category) {
                return $paymentCategory;
            }
        }

        return $this->payment_category;
    }

    public function getMoneyOutType()
    {
        foreach (self::getPaymentCategories() as $paymentCategory => $paymentType) {
            if ($paymentType == $this->payment_type) {
                return $paymentType;
            }
        }

        return $this->payment_type;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
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

//
//    /**
//     * @return Collection<int, MoneyOut>
//     */
//    public function getPaymentCategories(): Collection
//    {
//        return $this->categoryTypes;
//    }
//
//    public function addPaymentCategory(MoneyOut $categoryTypes): self
//    {
//        if (!$this->categoryTypes->contains($categoryTypes)) {
//            $this->categoryTypes->add($categoryTypes);
//            $categoryTypes->setPaymentCategory($this);
//        }
//
//        return $this;
//    }
//
//    public function removePaymentCategory(MoneyOut $categoryTypes): self
//    {
//        if ($this->categoryTypes->removeElement($categoryTypes)) {
//            // set the owning side to null (unless already changed)
//            if ($categoryTypes->getPaymentCategory() === $this) {
//                $categoryTypes->setPaymentCategory(null);
//            }
//        }
//
//        return $this;
//    }
}
