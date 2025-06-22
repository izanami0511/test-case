<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Repository\DoctorRepository;
use App\State\DoctorRegistrationProcessor;
use Doctrine\ORM\Mapping as ORM;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: DoctorRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: 'doctor/registration',
            denormalizationContext: ['groups' => ['user:register', 'doctor:register']],
            output: JWTAuthenticationSuccessResponse::class,
            name: 'api_doctor_register',
            processor: DoctorRegistrationProcessor::class
        ),
        new Get()
    ],
    normalizationContext: ['groups' => ['basic', 'doctor:read']],
)]
class Doctor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'doctor', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiProperty(
        readableLink: true,
        writableLink: true
    )]
    #[Groups(['user:register', 'doctor:register'])]
    private ?User $profile = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['doctor:read', 'doctor:register'])]
    #[ApiProperty(
        example: '[{"dayOfWeek":"monday","timeSlots":["09:00-12:00","14:00-18:00"]}]'
    )]
    private ?array $schedule = null;

    #[ORM\ManyToOne]
    #[Groups(['doctor:register'])]
    #[ApiProperty(writableLink: false, example: '/api/specialties/{id}')]
    private ?Specialty $specialty = null;

    public function __toString(): string
    {
        return $this->getProfile()->getFullname() ?? 'Новый';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProfile(): ?User
    {
        return $this->profile;
    }

    public function setProfile(User $profile): static
    {
        $this->profile = $profile;

        return $this;
    }

    public function getSchedule(): ?array
    {
        return $this->schedule;
    }

    public function setSchedule(?array $schedule): static
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function getMail(): string
    {
        return $this->getProfile()->getEmail();
    }

    public function getSpecialty(): ?Specialty
    {
        return $this->specialty;
    }

    public function setSpecialty(?Specialty $specialty): static
    {
        $this->specialty = $specialty;

        return $this;
    }
}
