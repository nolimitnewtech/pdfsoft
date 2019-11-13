<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FiliereController extends AbstractController
{
    /**
     * @Route("/filiere", name="filiere")
     */
    public function filiere()
    {
        return $this->render('filiere/filiere.html.twig', [
            'controller_name' => 'FiliereController',
        ]);
    }
    /**
     * @Route("/listefiliere", name="listefiliere")
     */
    public function listefiliere()
    {
        return $this->render('filiere/listefiliere.html.twig', [
            'controller_name' => 'FiliereController',
        ]);
    }
}
