<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Appointment;
use App\Service\MailService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class AppointmentPostProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private readonly ProcessorInterface $persistProcessor,
        private readonly Security $security,
        private readonly MailService $mailService,
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!$data instanceof Appointment) {
            throw new \InvalidArgumentException('Expected Appointment entity');
        }

        $user = $this->security->getUser();
        $data->setUser($user);

        $this->mailService->sendMail($data);

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
