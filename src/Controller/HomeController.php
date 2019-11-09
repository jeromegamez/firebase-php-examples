<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->json([
            'links' => [
                'database' => $this->generateUrl('database'),
                'users' => $this->generateUrl('users'),
            ],
        ]);
    }
}
