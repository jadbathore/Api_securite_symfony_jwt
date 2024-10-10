<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;

#[ApiResource(
    operations: [
        new Get(name:'companies_get_one'),
        new GetCollection(name:'companies_get_all'),
    ]
    )]
#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $SiretNum = null;

    #[ORM\Column(length: 255)]
    private ?string $adress = null;

    #[ORM\Column(length: 255)]
    private ?string $roleUser = null;

    /**
     * @var Collection<int, User>
     */

    /**
     * @var Collection<int, projet>
     */
    #[ORM\OneToMany(targetEntity: projet::class, mappedBy: 'company')]
    private Collection $Projets;

    #[ORM\ManyToOne(inversedBy: 'companies')]
    private ?user $user = null;

    // /**
    //  * @var Collection<int, User>
    //  */
    // #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'companies')]
    // private Collection $User;

    public function __construct()
    {
        $this->Projets = new ArrayCollection();
        // $this->User = new ArrayCollection();
    }

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSiretNum(): ?int
    {
        return $this->SiretNum;
    }

    public function setSiretNum(int $SiretNum): static
    {
        $this->SiretNum = $SiretNum;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getRoleUser(): ?string
    {
        return $this->roleUser;
    }

    public function setRoleUser(string $roleUser): static
    {
        $this->roleUser = $roleUser;

        return $this;
    }


    

    /**
     * @return Collection<int, projet>
     */
    public function getProjets(): Collection
    {
        return $this->Projets;
    }

    public function addProjet(projet $projet): static
    {
        if (!$this->Projets->contains($projet)) {
            $this->Projets->add($projet);
            $projet->setCompany($this);
        }

        return $this;
    }

    public function removeProjet(projet $projet): static
    {
        if ($this->Projets->removeElement($projet)) {
            // set the owning side to null (unless already changed)
            if ($projet->getCompany() === $this) {
                $projet->setCompany(null);
            }
        }

        return $this;
    }

    // /**
    //  * @return Collection<int, User>
    //  */
    // public function getUser(): Collection
    // {
    //     return $this->User;
    // }

    // public function addUser(User $user): static
    // {
    //     if (!$this->User->contains($user)) {
    //         $this->User->add($user);
    //     }

    //     return $this;
    // }

    // public function removeUser(User $user): static
    // {
    //     $this->User->removeElement($user);

    //     return $this;
    // }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }

}
