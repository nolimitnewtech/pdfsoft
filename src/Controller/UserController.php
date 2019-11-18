<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

     /**
     * @Route("/admin/profil", name="profil")
     */
    public function profil(Request $request)
    {
        $message = "";
        $user = $this->getUser();
        if ($request->isMethod('POST')) {
            $nom = $request->request->get("nom");
            $prenom = $request->request->get("prenom");
            $telephone = $request->request->get("telephone");

            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setTelephone($telephone);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $message = "Enregistrement effectué avec succes";
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
          }
        return $this->render('user/profil.html.twig', [
            'user'=>$user,
            'message'=>$message,
        ]);
    }

    /**
     * @Route("/admin/changer-mot-passe", name="changermotpasse")
     */
    public function changermotpasse(Request $request,UserPasswordEncoderInterface $encoder){

        $message = "";
        $user = $this->getUser();
        if ($request->isMethod('POST')) {
            $ancien = $request->request->get("ancienmotpasse");
            $nouveau1 = $request->request->get("nouveau1");
            $nouveau2 = $request->request->get("nouveau2");

            $encoded = $encoder->encodePassword($user, $ancien);
           
            if($encoded == $user->getPassword()){
              if($nouveau1 == $nouveau2){

                //password ok
                $encodednouveau = $encoder->encodePassword($user, $nouveau2);
                $user->setPassword($encodednouveau);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $message="enregistrement de nouveau mot de passe ok";

              }else{
                $message= "Les deux nouveaux mots de passe ne correspondent pas";
              }
            }else{
                $message= "Ancien mot de passe erroné";
            }



        }
        return $this->render('user/changermotpasse.html.twig', [
            'user'=>$user,
            'message'=>$message,
        ]);
    }
}
