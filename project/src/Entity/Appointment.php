<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Repository\AppointmentRepository;
use App\State\AppointmentPostProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            denormalizationContext: ['groups' => ['appointment:write']],
            security: "is_granted('ROLE_USER')",
            validationContext: ['groups' => ['appointment:write']],
            processor: AppointmentPostProcessor::class
        )
    ]
)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\Column]
    #[Assert\NotNull(groups: ['appointment:write'])]
    #[Assert\GreaterThan('today', groups: ['appointment:write'])]
    #[Groups(['appointment:write'])]
    //#[Validator] TODO: Здесь нужен кастомный валидатор на проверку доступности записи
    private ?\DateTimeImmutable $visitDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['appointment:write'])]
    private ?string $comment = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(groups: ['appointment:write'])]
    #[Groups(['appointment:write'])]
    #[ApiProperty(writableLink: false, example: '/api/doctors/{id}')]
    private ?Doctor $doctor = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getVisitDate(): ?\DateTimeImmutable
    {
        return $this->visitDate;
    }

    public function setVisitDate(\DateTimeImmutable $visitDate): static
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(?Doctor $doctor): static
    {
        $this->doctor = $doctor;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
