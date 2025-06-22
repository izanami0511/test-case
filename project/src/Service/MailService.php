<?php

namespace App\Service;

use App\Entity\Appointment;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;

readonly class MailService
{
    public function __construct(
        private MailerInterface       $mailer,
        private ParameterBagInterface $params,

    )
    {
    }

    public function sendMail($entity): void
    {
        match (get_class($entity)) {
            Appointment::class => $this->sendAppointmentMail($entity),
            User::class => $this->sendRegistrationMail($entity),
            default => null
        };
    }

    public function sendAppointmentMail(Appointment $appointment): void
    {
        $email = (new TemplatedEmail())
            ->from($this->params->get('app.mailer.from'))
            ->to($appointment->getDoctor()->getMail())
            ->subject("Запись к врачу")
            ->htmlTemplate('mail/appointment.html.twig')
            ->context([
                'appointment' => $appointment
            ]);

        $this->mailer->send($email);
    }

    public function sendRegistrationMail(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from($this->params->get('app.mailer.from'))
            ->to($user->getEmail())
            ->subject("Регистрация")
            ->htmlTemplate('mail/user.html.twig')
            ->context([
                'user' => $user
            ]);

        $this->mailer->send($email);
    }
}
