<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CoursesController extends AbstractController
{

/**
     * @Route("/home", name="home")
     */
    public function index()
    {
        return $this->render('cours/index.html.twig', [
            'controller_name' => 'CoursesController',
        ]);
    }    
    /**
     * @Route("/cours", name="cours")
     */
    public function cours()
    {
        return $this->render('cours/cours.html.twig', [
            'controller_name' => 'CoursesController',
        ]);
    }
    /**
     * @Route("/listecours", name="listcours")
     */
    public function listecours()
    {
        return $this->render('cours/listecours.html.twig', [
            'controller_name' => 'CoursesController',
        ]);
    }

}
