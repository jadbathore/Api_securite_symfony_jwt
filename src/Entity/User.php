<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Company>
     */
    #[ORM\OneToMany(targetEntity: Company::class, mappedBy: 'user')]
    private Collection $companies;

    public function __construct()
    {
        $this->companies = new ArrayCollection();
    }

    // /**
    //  * @var Collection<int, Company>
    //  */
    // #[ORM\ManyToMany(targetEntity: Company::class, mappedBy: 'User')]
    // private Collection $companies;

    // public function __construct()
    // {
    //     // $this->companies = new ArrayCollection();
    // }

    /**
     * @var Collection<int, Company>
     */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
    * A visual identifier that represents this user.
    *
    * @see UserInterface
    */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @return list<string>
     * @see UserInterface
     *
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    
    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Company>
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    // public function addCompany(Company $company): static
    // {
    //     if (!$this->companies->contains($company)) {
    //         $this->companies->add($company);
    //         $company->addUser($this);
    //     }

    //     return $this;
    // }

    // public function removeCompany(Company $company): static
    // {
    //     if ($this->companies->removeElement($company)) {
    //         $company->removeUser($this);
    //     }

    //     return $this;
    // }

    public function addCompany(Company $company): static
    {
        if (!$this->companies->contains($company)) {
            $this->companies->add($company);
            $company->setUser($this);
        }

        return $this;
    }

    public function removeCompany(Company $company): static
    {
        if ($this->companies->removeElement($company)) {
            // set the owning side to null (unless already changed)
            if ($company->getUser() === $this) {
                $company->setUser(null);
            }
        }

        return $this;
    }


}
