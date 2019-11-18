<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(AuthenticationUtils $authenticationUtils)
    {
                // retrouver une erreur d'authentification s'il y en a une
                $error = $authenticationUtils->getLastAuthenticationError();
                // retrouver le dernier identifiant de connexion utilisé
                $lastUsername = $authenticationUtils->getLastUsername();
              

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    

    /**
     * @Route("/login-check", name="login_check")
     */    
    public function login_check(){
                // retrouver une erreur d'authentification s'il y en a une
                $error = $authenticationUtils->getLastAuthenticationError();
                // retrouver le dernier identifiant de connexion utilisé
                $lastUsername = $authenticationUtils->getLastUsername();
              

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }
}
