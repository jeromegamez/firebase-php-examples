<?php

namespace App\Controller;

use Kreait\Firebase\Database;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DatabaseController extends AbstractController
{
    /** @var Database */
    private $database;

    public function __construct(Database $firebase)
    {
        $this->database = $firebase;
    }

    /**
     * @Route("/database", name="database")
     */
    public function index(): JsonResponse
    {
        $reference = '/';
        $snapshot = $this->database->getReference($reference)->getSnapshot();

        return $this->json([
            'reference' => $reference,
            'num_children' => $snapshot->numChildren(),
            'children' => $snapshot->hasChildren() ? array_keys($snapshot->getValue()) : null,
        ]);
    }
}
