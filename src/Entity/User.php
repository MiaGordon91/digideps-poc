<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = 1;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 180)]
    private ?string $deputyFirstName = null;

    #[ORM\Column(length: 180)]
    private ?string $deputyLastName = null;

    #[ORM\Column(length: 180)]
    private ?string $clientsFirstNames = null;

    #[ORM\Column(length: 180)]
    private ?string $clientsLastName = null;

    #[ORM\Column(length: 180)]
    private ?string $clientsCaseNumber = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

//    Might not need this, can be deleted if not required
    #[ORM\OneToMany(mappedBy: 'deputyUser', targetEntity: MoneyOut::class)]
    private Collection $moneyOutPayments;

    public function __construct()
    {
        $this->moneyOutPayments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDeputyFirstName(): ?string
    {
        return $this->deputyFirstName;
    }

    public function setDeputyFirstName(string $deputyFirstName): self
    {
        $this->deputyFirstName = $deputyFirstName;

        return $this;
    }

    public function getDeputyLastName(): ?string
    {
        return $this->deputyLastName;
    }

    public function setDeputyLastName(string $deputyLastName): self
    {
        $this->deputyLastName = $deputyLastName;

        return $this;
    }

    public function getClientsFirstNames(): ?string
    {
        return $this->clientsFirstNames;
    }

    public function setClientsFirstNames(string $clientsFirstNames): self
    {
        $this->clientsFirstNames = $clientsFirstNames;

        return $this;
    }

    public function getClientsLastName(): ?string
    {
        return $this->clientsLastName;
    }

    public function setClientsLastName(string $clientsLastName): self
    {
        $this->clientsLastName = $clientsLastName;

        return $this;
    }

    public function getClientsCaseNumber(): ?string
    {
        return $this->clientsCaseNumber;
    }

    public function setClientsCaseNumber(string $clientsCaseNumber): self
    {
        $this->clientsCaseNumber = $clientsCaseNumber;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, MoneyOut>
     */
    public function getMoneyOutPayments(): Collection
    {
        return $this->moneyOutPayments;
    }

    public function addMoneyOutPayment(MoneyOut $moneyOutPayment): self
    {
        if (!$this->moneyOutPayments->contains($moneyOutPayment)) {
            $this->moneyOutPayments->add($moneyOutPayment);
            $moneyOutPayment->setUserId($this);
        }

        return $this;
    }

    public function removeMoneyOutPayment(MoneyOut $moneyOutPayment): self
    {
        if ($this->moneyOutPayments->removeElement($moneyOutPayment)) {
            // set the owning side to null (unless already changed)
            if ($moneyOutPayment->getUserId() === $this) {
                $moneyOutPayment->setUserId(null);
            }
        }

        return $this;
    }
}
