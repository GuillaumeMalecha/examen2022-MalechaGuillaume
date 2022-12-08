<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChansonController extends AbstractController
{
    /**
     * @Route("/chanson", name="app_chanson")
     */
    public function index(): Response
    {
        return $this->render('chanson/index.html.twig', [
            'controller_name' => 'ChansonController',
        ]);
    }
}
