<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AbonnementController extends AbstractController
{
    /**
     * @Route("/admin/abonnement", name="abonnement")
     */
    public function abonnement()
    {
        return $this->render('abonnement/abonnement.html.twig', [
            'controller_name' => 'AbonnementController',
        ]);
    }
    /**
     * @Route("/listeabonnement", name="listeabonnement")
     */
    public function listeabonnement()
    {
        return $this->render('abonnement/listeabonnement.html.twig', [
            'controller_name' => 'AbonnementController',
        ]);
    }
}
