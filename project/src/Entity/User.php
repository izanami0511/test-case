<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\UserRepository;
use App\State\UserLoginProcessor;
use App\State\UserRegistrationProcessor;
use Doctrine\ORM\Mapping as ORM;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: 'registration',
            denormalizationContext: ['groups' => ['user:register']],
            validationContext: ['groups' => ['user:register']],
            name: 'api_register',
            processor: UserRegistrationProcessor::class
        ),
        new Post(
            uriTemplate: 'authentication',
            denormalizationContext: ['groups' => ['user:auth']],
            output: JWTAuthenticationSuccessResponse::class,
            name: 'api_login',
            processor: UserLoginProcessor::class
        ),
        new Patch(
            denormalizationContext: ['groups' => ['user:update']],
            security: 'object == user',
        ),
        new Delete(security: 'is_granted(\"ROLE_ADMIN\")')
    ],
    normalizationContext: ['groups' => ['basic', 'user:read']],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(['basic', 'user:register', 'user:auth'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['user:read'])]
    private array $roles = [];

    #[ORM\Column]
    #[Assert\NotBlank()]
    #[Groups(['user:register'])]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['basic', 'user:register'])]
    private ?string $fullname = null;

    #[ORM\Column(length: 16, nullable: true)]
    #[Groups(['basic', 'user:register'])]
    private ?string $phone = null;

    #[ORM\Column(length: 32, nullable: true)]
    #[Groups(['user:read', 'user:register'])]
    private ?string $birthdate = null;

    #[ORM\OneToOne(mappedBy: 'profile', cascade: ['persist'])]
    private ?Doctor $doctor = null;

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

    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): static
    {
        $this->fullname = $fullname;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getBirthdate(): ?string
    {
        return $this->birthdate;
    }

    public function setBirthdate(?string $birthdate): static
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(?Doctor $doctor): static
    {
        if ($doctor !== null && $doctor->getProfile() !== $this) {
            $doctor->setProfile($this);
        }
        $this->doctor = $doctor;
        return $this;
    }
}
