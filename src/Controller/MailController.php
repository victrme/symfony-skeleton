<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class MailController extends AbstractController
{
    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/mail', name: 'app_mail')]
    public function __invoke(MailerInterface $mailer): Response
    {
        $mailer->send(
            new Email()
                ->from('noreply@example.com')
                ->to('client@domain.com')
                ->subject('Hello World !')
                ->text('This email has been correctly sent !')
        );

        return $this->redirectToRoute('app_home');
    }
}
