<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NiveauController extends AbstractController
{
    /**
     * @Route("/niveau", name="niveau")
     */
    public function niveau()
    {
        return $this->render('niveau/niveau.html.twig', [
            'controller_name' => 'NiveauController',
        ]);
    }
    /**
     * @Route("/listeniveau", name="listeniveau")
     */
    public function listeniveau()
    {
        return $this->render('niveau/listeniveau.html.twig', [
            'controller_name' => 'NiveauController',
        ]);
    }
}
