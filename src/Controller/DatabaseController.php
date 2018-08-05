<?php

namespace App\Controller;

use Kreait\Firebase;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DatabaseController extends Controller
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
     * @Route("/database", name="database")
     */
    public function index()
    {
        $reference = '/';
        $snapshot = $this->firebase->getDatabase()->getReference($reference)->getSnapshot();

        return $this->json([
            'reference' => $reference,
            'num_children' => $snapshot->numChildren(),
            'children' => $snapshot->hasChildren() ? array_keys($snapshot->getValue()) : null,
        ]);
    }
}
