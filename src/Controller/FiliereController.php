<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Filiere;
use App\Form\FiliereType;

class FiliereController extends AbstractController
{
    /**
     * @Route("/admin/filiere", name="filiere")
     */
    public function filiere(Request $request)
    {
        $filiere = new Filiere();
        $form   = $this->get('form.factory')->create(FiliereType::class, $filiere);    
        $message = "";
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($filiere);
            $em->flush();
            $message = "Enregistrement effectué avec succes";
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
            $filiere = new Filiere();
            $form   = $this->get('form.factory')->create(FiliereType::class, $filiere);  
          }

        return $this->render('filiere/filiere.html.twig', [
            'controller_name' => 'UniversiteController',
            'form'=>$form->createView(),
            'message'=>$message,
        ]);
    }
    /**
     * @Route("/admin/listefiliere", name="listefiliere")
     */
    public function listefiliere()
    {

        $repository = $this->getDoctrine()->getRepository(Filiere::class);
        $filieres = $repository->findAll();
        return $this->render('filiere/listefiliere.html.twig', [
            'lesfilieres'=>$filieres,
        ]);
    }




    
    /**
     * @Route("/admin/modifier-filiere/{id}", name="modifierfiliere")
     */
    public function modifierfiliere(Request $request,$id)
    {
       
        $repository = $this->getDoctrine()->getRepository(Filiere::class);
        $filiere = $repository->find($id);   
        $form   = $this->get('form.factory')->create(FiliereType::class, $filiere);    
        $message = "";
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($filiere);
            $em->flush();
            $message = "Enregistrement effectué avec succes";
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
          }

        return $this->render('filiere/modifierfiliere.html.twig', [
            'form'=>$form->createView(),
            'message'=>$message,
        ]);
    }

    /**
     * @Route("/admin/supprimer-filiere/{id}", name="supprimerfiliere")
     */
    public function supprimerfiliere(Request $request,$id)
    {
       
        $repository = $this->getDoctrine()->getRepository(Filiere::class);
        $filiere = $repository->find($id);   
        $message = "";
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($filiere);
            $em->flush();
         
            // redirection
            return $this->redirectToRoute('listefiliere');
          }

        return $this->render('filiere/supprimerfiliere.html.twig', [
            'message'=>$message,
        ]);
    }
}
