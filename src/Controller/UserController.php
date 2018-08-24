<?php

namespace App\Controller;

use Kreait\Firebase;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @var Firebase
     */
    private $firebase;

    public function __construct(Firebase $firebase)
    {
        $this->firebase = $firebase;
    }

    /**
     * @Route("/users", name="users")
     */
    public function index(): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $users = iterator_to_array($this->firebase->getAuth()->listUsers());

        return $this->json([
            'data' => $users,
        ]);
    }
}
