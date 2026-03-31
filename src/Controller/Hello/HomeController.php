<?php

namespace App\Controller\Hello;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/hello')]
final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_hello')]
    public function __invoke(): Response
    {
        return $this->render('pages/hello.html.twig');
    }
}
