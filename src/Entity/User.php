<?php

namespace App\Entity;

use App\Entity\Messages;
use App\Entity\Chart;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Security;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
//const
const ROLE_ADMIN ='ROLE_ADMIN';

const ROLE_TECHNICIAN ='ROLE_TECHNICIAN';



    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    // #[ORM\Column(length: 255,nullable: true)]

    // private ?string $imagePath = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Building::class, orphanRemoval: true)]
    private Collection $buildings;

    #[ORM\Column(length: 255)]
    private ?string $registrationNo = null;

    #[ORM\OneToMany(mappedBy: 'no', targetEntity: Participant::class)]
    private Collection $participant;

    #[ORM\ManyToMany(targetEntity: Roles::class, inversedBy: 'users')]
    private Collection $role;

    // #[ORM\OneToMany(mappedBy: 'user', targetEntity: Chart::class)]
    // private Collection $messages;

    public function __construct()
    {
        
        $this->buildings = new ArrayCollection();
        // $this->messages = new ArrayCollection();
        $this->participant = new ArrayCollection();
        $this->role = new ArrayCollection();
    }

    #[ORM\OneToOne(inversedBy: 'user', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
  

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

    public function getFirstName(): ?string
    {
        return $this->firstName;
        
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    // public function getImagePath(): ?string
    // {
    //     return $this->imagePath;
    // }

    // public function setImagePath(string $imagePath): self
    // {
    //     $this->imagePath = $imagePath;

    //     return $this;
    // }

    /**
     * @return Collection<int, Building>
     */
    public function getBuildings(): Collection
    {
        return $this->buildings;
    }

    public function addBuilding(Building $building): self
    {
        if (!$this->buildings->contains($building)) {
            $this->buildings->add($building);
            $building->setUser($this);
        }

        return $this;
    }

    public function removeBuilding(Building $building): self
    {
        if ($this->buildings->removeElement($building)) {
            // set the owning side to null (unless already changed)
            if ($building->getUser() === $this) {
                $building->setUser(null);
            }
        }

        return $this;
    }

    public function getRegistrationNo(): ?string
    {
        return $this->registrationNo;
    }

    public function setRegistrationNo(string $registrationNo): self
    {
        $this->registrationNo = $registrationNo;

        return $this;
    }


    public function isAdmin():bool
    {
       return in_array(self::ROLE_ADMIN,$this->getRoles());
    }


    public function isTechnician():bool
    {
       return in_array(self::ROLE_TECHNICIAN,$this->getRoles());
    }

   
    public function __toString() {
        return $this->firstName;
    }

    // /**
    //  * @return Collection<int, Chart>
    //  */
    // public function getMessages(): Collection
    // {
    //     return $this->messages;
    // }

    // public function addMessage(Chart $message): self
    // {
    //     if (!$this->messages->contains($message)) {
    //         $this->messages->add($message);
    //         $message->setUser($this);
    //     }

    //     return $this;
    // }

    // public function removeMessage(Chart $message): self
    // {
    //     if ($this->messages->removeElement($message)) {
    //         // set the owning side to null (unless already changed)
    //         if ($message->getUser() === $this) {
    //             $message->setUser(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipant(): Collection
    {
        return $this->participant;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participant->contains($participant)) {
            $this->participant->add($participant);
            // $participant->setNo($this);
        }

        return $this;
    }

    // public function removeParticipant(Participant $participant): self
    // {
    //     if ($this->participant->removeElement($participant)) {
    //         // set the owning side to null (unless already changed)
    //         // if ($participant->getNo() === $this) {
    //             // $participant->setNo(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, Roles>
     */
    public function getRole(): Collection
    {
        return $this->role;
    }

    public function addRole(Roles $role): self
    {
        if (!$this->role->contains($role)) {
            $this->role->add($role);
        }

        return $this;
    }

    public function removeRole(Roles $role): self
    {
        $this->role->removeElement($role);

        return $this;
    }
}
