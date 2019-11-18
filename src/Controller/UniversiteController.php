<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Universite;
use App\Form\UniversType;

class UniversiteController extends AbstractController
{
    /**
     * @Route("/admin/universite", name="universite")
     */
    public function universite(Request $request)
    {
        $universite = new Universite();
        $form   = $this->get('form.factory')->create(UniversType::class, $universite);    
        $message = "";
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($universite);
            $em->flush();
            $message = "Enregistrement effectué avec succes";
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
            $universite = new Universite();
            $form   = $this->get('form.factory')->create(UniversType::class, $universite);  
          }

        return $this->render('universite/universite.html.twig', [
            'controller_name' => 'UniversiteController',
            'form'=>$form->createView(),
            'message'=>$message,
        ]);
    }
    /**
     * @Route("/admin/listeuniversite", name="listeuniversite")
     */
    public function listeuniversite(Request $request)
    {
       

        $repository = $this->getDoctrine()->getRepository(Universite::class);
         $universites = $repository->findAll();
        return $this->render('universite/listeuniversite.html.twig', [
            'controller_name' => 'UniversiteController',
            'lesuniversites'=>$universites,
        ]);
    }

    /**
     * @Route("/admin/modifier-universite/{id}", name="modifieruniversite")
     */
    public function modifieruniversite(Request $request,$id)
    {
       
        $repository = $this->getDoctrine()->getRepository(Universite::class);
        $universite = $repository->find($id);   
        $form   = $this->get('form.factory')->create(UniversType::class, $universite);    
        $message = "";
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($universite);
            $em->flush();
            $message = "Enregistrement effectué avec succes";
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
          }

        return $this->render('universite/modifieruniversite.html.twig', [
            'controller_name' => 'UniversiteController',
            'form'=>$form->createView(),
            'message'=>$message,
        ]);
    }

    /**
     * @Route("/admin/supprimer-universite/{id}", name="supprimeruniversite")
     */
    public function supprimeruniversite(Request $request,$id)
    {
       
        $repository = $this->getDoctrine()->getRepository(Universite::class);
        $universite = $repository->find($id);   
        $message = "";
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($universite);
            $em->flush();
         
            // redirection
            return $this->redirectToRoute('listeuniversite');
          }

        return $this->render('universite/supprimeruniversite.html.twig', [
            'controller_name' => 'UniversiteController',
            'message'=>$message,
        ]);
    }

}
