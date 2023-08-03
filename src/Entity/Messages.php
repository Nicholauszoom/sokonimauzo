<?php

namespace App\Entity;


use App\Repository\MessagesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\SecurityBundle\Security;
use Gedmo\Mapping\Annotation as Gedmo;


#[ORM\Entity(repositoryClass: MessagesRepository::class)]
class Messages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagePath = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageData = null;
    //  #[ORM\Column(length: 255, nullable: true)]
    // private ?string $cameraImage = null;

    // #[ORM\ManyToOne(inversedBy: 'messages')]
    // #[ORM\JoinColumn(nullable: false)]
    // private ?Infrastructure $infrastructure = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Classroom $classroom = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Building $building = null;

    // #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    // private ?\DateTimeInterface $time = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

  
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $latitude =null;

 
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $longitude =null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Officess $office = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on:'create')]
    private ?\DateTimeImmutable $createdAt = null;

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

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

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }
    public function getImageData(): ?string
    {
        return $this->imageData;
    }

    public function setImageData(?string $imageData): self
    {
        $this->imageData = $imageData;

        return $this;
    }

    // public function getInfrastructure(): ?Infrastructure
    // {
    //     return $this->infrastructure;
    // }

    // public function setInfrastructure(?Infrastructure $infrastructure): self
    // {
    //     $this->infrastructure = $infrastructure;

    //     return $this;
    // }

    public function getClassroom(): ?Classroom
    {
        return $this->classroom;
    }

    public function setClassroom(?Classroom $classroom): self
    {
        $this->classroom = $classroom;

        return $this;
    }

    public function getBuilding(): ?Building
    {
        return $this->building;
    }

    public function setBuilding(?Building $building): self
    {
        $this->building = $building;

        return $this;
    }

    // public function getTime(): ?\DateTimeInterface
    // {
    //     return $this->time;
    // }

    // public function setTime(\DateTimeInterface $time): self
    // {
    //     $this->time = $time;

    //     return $this;
    // }
    public function __toString() {
        return $this->title;
    }

    public function getUser(): ?User
    {
       
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
    // public function someMethod(User): void
    // {
    //     // returns User object or null if not authenticated
    //     $user = $this->security->getUser();

    //     // ...
    // }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getOffice(): ?Officess
    {
        return $this->office;
    }

    public function setOffice(?Officess $office): self
    {
        $this->office = $office;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

}
