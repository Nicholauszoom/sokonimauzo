<?php

namespace App\Entity;

use App\Repository\TechnicianRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: TechnicianRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]

class Technician  implements UserInterface, PasswordAuthenticatedUserInterface{
// class Technician implements UserInterface, PasswordAuthenticatedUserInterface{
// implements UserInterface, PasswordAuthenticatedUserInterface

// const ROLE_TECHNICIAN = 'ROLE_TECHNICIAN';

const ROLE_ADMIN ='ROLE_ADMIN';

const ROLE_TECHNICIAN ='ROLE_TECHNICIAN';



    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];


    #[ORM\Column(length: 255)]
    private ?string $profession = null;

  /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
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
       

        return ['ROLE_TECHNICIAN'];
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



    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }


    public function __toString() {
        return $this->email;
    }

    public function isAdmin():bool
    {
       return in_array(self::ROLE_ADMIN,$this->getRoles());
    }


    public function isTechnician():bool
    {
       return in_array(self::ROLE_TECHNICIAN,$this->getRoles());
    }
   
}
