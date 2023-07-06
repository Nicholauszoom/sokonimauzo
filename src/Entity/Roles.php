<?php

namespace App\Entity;

use App\Repository\RolesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RolesRepository::class)]
class Roles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;
    #[ORM\Column]
    private ?bool $status = null;

    // #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'role')]
    // private Collection $users;

    // public function __construct()
    // {
    //     $this->users = new ArrayCollection();
    // }

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'roles')]
    #[ORM\JoinColumn(nullable: false)]
    // #[Assert\NotBlank]
    // private ?self $roles = null;

    // public function __construct()
    // {
    //     $this->roles = new ArrayCollection();
    // }

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

    // public function getRoles(): ?self
    // {
    //     return $this->roles;
    // }

    // public function setRoles(?self $roles): self
    // {
    //     $this->roles = $roles;

    //     return $this;
    // }
    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    // public function addRole(self $role): self
    // {
    //     if (!$this->roles->contains($role)) {
    //         $this->roles->add($role);
    //         $role->setRoles($this);
    //     }

    //     return $this;
    // }

    // public function removeRole(self $role): self
    // {
    //     if ($this->roles->removeElement($role)) {
    //         // set the owning side to null (unless already changed)
    //         if ($role->getRoles() === $this) {
    //             $role->setRoles(null);
    //         }
    //     }

    //     return $this;
    // }

    // /**
    //  * @return Collection<int, User>
    //  */
    // public function getUsers(): Collection
    // {
    //     return $this->users;
    // }

    // public function addUser(User $user): self
    // {
    //     if (!$this->users->contains($user)) {
    //         $this->users->add($user);
    //         $user->addRole($this);
    //     }

    //     return $this;
    // }

    // public function removeUser(User $user): self
    // {
    //     if ($this->users->removeElement($user)) {
    //         $user->removeRole($this);
    //     }

    //     return $this;
    // }
    public function __toString() {
        return $this->name;
    }
}