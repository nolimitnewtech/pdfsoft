<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use App\Entity\Universite;
use App\Entity\Filiere;
use App\Entity\Niveau;
use App\Entity\Student;
use App\Entity\User;
use App\Entity\Mail;
use App\Entity\Cours;
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
     * @Route("/profil", name="profil")
     */
    public function profil(Request $request)
    {
   

            // redirection
    $user = $this->getUser();
    $role = $user->getRoles()[0];
    if($role == "ROLE_ADMIN"){
        return $this->redirectToRoute('profiladmin');
    }

    if($role == "ROLE_USER"){
        return $this->redirectToRoute('profilstudent');
    }

    return $this->render('login/account.html.twig', [
    ]);
    }

    /**
     * @Route("/admin/modifier-son-profil", name="profiladmin")
     */

     public function profiladmin(Request $request){
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
          return $this->render('user/profiladmin.html.twig', [
            'user'=>$user,
            'message'=>$message,
        ]);
     }

    /**
     * @Route("/student/modifier-son-profil", name="profilstudent")
     */

    public function profilstudent(Request $request){
        $message = "";
        $user = $this->getUser();

        $repository = $this->getDoctrine()->getRepository(Filiere::class);
        $filieres = $repository->findAll();

        $repository = $this->getDoctrine()->getRepository(Universite::class);
        $universites = $repository->findAll();

        $repository = $this->getDoctrine()->getRepository(Niveau::class);
        $niveaux = $repository->findAll();

        $repository = $this->getDoctrine()->getRepository(Student::class);
        $students = $repository->findBy( ['user' => $user]); 
        $student = null;
        foreach ( $students as $etudiant){
            $student = $etudiant;
        }

        if ($request->isMethod('POST')) {
            $nom = $request->request->get("nom");
            $prenom = $request->request->get("prenom");
            $telephone = $request->request->get("telephone");

            $idniveau = $request->request->get("niveau");
            $idfiliere = $request->request->get("filiere");
            $iduniversite = $request->request->get("universite");

            $repository = $this->getDoctrine()->getRepository(Niveau::class);
            $niveau = $repository->find($idniveau);

 
            $repository = $this->getDoctrine()->getRepository(Filiere::class);
            $filiere = $repository->find($idfiliere);

            $repository = $this->getDoctrine()->getRepository(Universite::class);
            $universite = $repository->find($iduniversite);

            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setTelephone($telephone);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $student->setNiveau($niveau);
            $student->setUniversite($universite);
            $student->setFiliere($filiere);
            $em->persist($student);
            $em->flush();

            $message = "Enregistrement effectué avec succes";
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
          }
          return $this->render('user/profilstudent.html.twig', [
            'user'=>$user,
            'message'=>$message,
            'student'=>$student,
            'niveaux'=>$niveaux,
            'filieres'=>$filieres,
            'universites'=>$universites,
        ]);
    }

    /**
     * @Route("/profil/changer-mot-passe", name="changermotpasse")
     */
    public function changermotpasse(Request $request,UserPasswordEncoderInterface $encoder){

        $user = $this->getUser();
        $role = $user->getRoles()[0];
        if($role == "ROLE_ADMIN"){
            return $this->redirectToRoute('changermotpasseadmin');
        }
    
        if($role == "ROLE_USER"){
            return $this->redirectToRoute('changermotpassestudent');
        }
    
        return $this->render('login/account.html.twig', [
        ]);

    
    }

    
    /**
     * @Route("/admin/changer-mot-passe", name="changermotpasseadmin")
     */
    public function changermotpasseadmin(Request $request,UserPasswordEncoderInterface $encoder){
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


    /**
     * @Route("/student/changer-mot-passe", name="changermotpassestudent")
     */
    public function changermotpassestudent(Request $request,UserPasswordEncoderInterface $encoder){
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
        return $this->render('user/changermotpassestudent.html.twig', [
            'user'=>$user,
            'message'=>$message,
        ]); 
    }
}
