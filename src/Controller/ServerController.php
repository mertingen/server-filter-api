<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/server', name: 'app_server')]
class ServerController extends AbstractController
{
    #[Route('', name: 'app_server_index')]
    public function index(): Response
    {
        return $this->render('server/server.html.twig');
    }

}