<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class MailController extends AbstractController
{
    public function __construct(private readonly \Symfony\Component\Mailer\MailerInterface $mailer)
    {
    }
    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/mail', name: 'app_mail')]
    public function __invoke(): Response
    {
        $this->mailer->send(
            new Email()
                ->from('noreply@example.com')
                ->to('client@domain.com')
                ->subject('Hello World !')
                ->text('This email has been correctly sent !')
        );
        return $this->redirectToRoute('app_home');
    }
}
