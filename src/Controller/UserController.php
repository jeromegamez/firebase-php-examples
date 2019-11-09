<?php

namespace App\Controller;

use Kreait\Firebase\Auth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /** @var Auth */
    private $auth;

    public function __construct(Auth $firebase)
    {
        $this->auth = $firebase;
    }

    /**
     * @Route("/users", name="users")
     */
    public function index(): JsonResponse
    {
        return $this->json([
            'data' => iterator_to_array($this->auth->listUsers()),
        ]);
    }
}
