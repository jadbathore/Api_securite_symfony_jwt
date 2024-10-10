<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginTestController extends AbstractController
{
    /**
     * methode placeholder permettant de faire fonctionner l'identification JWT
     */
    #[Route('/api/auth', name: 'auth')]
    public function index()
    {}
}
