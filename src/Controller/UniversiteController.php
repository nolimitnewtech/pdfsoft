<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UniversiteController extends AbstractController
{
    /**
     * @Route("/universite", name="universite")
     */
    public function universite()
    {
        return $this->render('universite/universite.html.twig', [
            'controller_name' => 'UniversiteController',
        ]);
    }
    /**
     * @Route("/listeuniversite", name="listeuniversite")
     */
    public function listeuniversite()
    {
        return $this->render('universite/listeuniversite.html.twig', [
            'controller_name' => 'UniversiteController',
        ]);
    }
}
