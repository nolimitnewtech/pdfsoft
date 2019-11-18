<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Niveau;
use App\Form\NiveauType;

class NiveauController extends AbstractController
{
    /**
     * @Route("/admin/niveau", name="niveau")
     */
    public function niveau(Request $request)
    {
        $niveau = new Niveau();
        $form   = $this->get('form.factory')->create(NiveauType::class, $niveau);    
        $message = "";
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($niveau);
            $em->flush();
            $message = "Enregistrement effectué avec succes";
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
            $niveau = new Niveau();
            $form   = $this->get('form.factory')->create(NiveauType::class, $niveau);  
          }
        return $this->render('niveau/niveau.html.twig', [
            'controller_name' => 'NiveauController',
            'form'=>$form->createView(),
            'message'=>$message,
        ]);
    }
    /**
     * @Route("/admin/listeniveau", name="listeniveau")
     */
    public function listeniveau(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Niveau::class);
        $niveau = $repository->findAll();
       
        return $this->render('niveau/listeniveau.html.twig', [
            'controller_name' => 'NiveauController',           
           'lesniveaux'=>$niveau,
        ]);
    }

  /**
     * @Route("/admin/modifier-niveau/{id}", name="modifierniveau")
     */
    public function modifierniveau(Request $request,$id)
    {
       
        $repository = $this->getDoctrine()->getRepository(Niveau::class);
        $niveau = $repository->find($id);   
        $form   = $this->get('form.factory')->create(NiveauType::class, $niveau);    
        $message = "";
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($niveau);
            $em->flush();
            $message = "Enregistrement effectué avec succes";
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
          }

        return $this->render('niveau/modifierniveau.html.twig', [
            'form'=>$form->createView(),
            'message'=>$message,
        ]);
    }

    /**
     * @Route("/admin/supprimer-niveau/{id}", name="supprimerniveau")
     */
    public function supprimerniveau(Request $request,$id)
    {
       
        $repository = $this->getDoctrine()->getRepository(Niveau::class);
        $niveau = $repository->find($id);   
        $message = "";
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($niveau);
            $em->flush();
         
            // redirection
            return $this->redirectToRoute('listeniveau');
          }

        return $this->render('niveau/supprimerniveau.html.twig', [
            'message'=>$message,
        ]);
    }
}
