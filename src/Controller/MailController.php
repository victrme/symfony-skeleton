<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class MailController extends AbstractController
{
    public function __construct(private readonly MailerService $mailerService) {}

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/mail', name: 'app_mail')]
    public function __invoke(): Response
    {
        $this->mailerService->send('Hello World !', 'This email has been correctly sent !');

        return $this->redirectToRoute('app_home');
    }
}
