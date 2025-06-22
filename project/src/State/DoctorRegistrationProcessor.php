<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Doctor;
use App\Entity\User;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DoctorRegistrationProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly AuthenticationSuccessHandler $successHandler,
        private readonly MailService $mailService,
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        {
            if (!$data instanceof Doctor) {
                throw new \InvalidArgumentException('Expected Doctor entity');
            }

            $user = new User();
            $user->setEmail($data->getProfile()->getEmail());
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $data->getProfile()->getPassword()
                )
            );
            $user->setFullname($data->getProfile()->getFullname());
            $user->setRoles(['ROLE_DOCTOR']);

            $data->setProfile($user);

            $this->em->persist($user);
            $this->em->persist($data);
            $this->em->flush();
            $this->mailService->sendMail($user);

            return $this->successHandler->handleAuthenticationSuccess($user);
        }
    }
}
