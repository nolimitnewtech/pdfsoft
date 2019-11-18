<?php

namespace App\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use App\Entity\Cours;
use App\Entity\User;
use App\Form\CoursType;
use App\Form\FichierType;

class CoursesController extends AbstractController
{

/**
     * @Route("/admin/home", name="home")
     */
    public function index()
    {
        return $this->render('cours/index.html.twig', [
            'controller_name' => 'CoursesController',
        ]);
    }    
    /**
     * @Route("/admin/cours", name="cours")
     */
    public function cours(Request $request)
    {
        $cours = new Cours();
        $form   = $this->get('form.factory')->create(CoursType::class, $cours);    
        $message = "";
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                      /** @var UploadedFile $brochureFile */
                      $brochureFile = $form['brochure']->getData();
                      if ($brochureFile) {
                        $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                        // this is needed to safely include the file name as part of the URL
                        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
        
                        // Move the file to the directory where brochures are stored
                        try {
                            $brochureFile->move(
                                $this->getParameter('brochures_directory'),
                                $newFilename
                            );
                        } catch (FileException $e) {
                            // ... handle exception if something happens during file upload
                        }
        
                        // updates the 'brochureFilename' property to store the PDF file name
                        // instead of its contents
                        $cours->setLien($newFilename);
                        $cours->setDatepub(new \DateTime());
                        
                    }

                    // persiste cours
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($cours);
                    $em->flush();
                    $message = "Enregistrement effectué avec succes";
                    $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
                    $cours = new Cours();
                    $form   = $this->get('form.factory')->create(CoursType::class, $cours);  

          }
        return $this->render('cours/cours.html.twig', [
            'form'=>$form->createView(),
            'message'=>$message,
        ]);
    }
    /**
     * @Route("/admin/listecours", name="listcours")
     */
    public function listecours()
    {
        $repository = $this->getDoctrine()->getRepository(Cours::class);
        $cours = $repository->findAll();

        return $this->render('cours/listecours.html.twig', [
            'controller_name' => 'CoursesController',
            'cours'=>$cours,
        ]);
    }


    /**
     * @Route("/download/{lien}", name="download_file")
    **/
    public function downloadFileAction($lien){
        $urlPattern  = $this->getParameter('brochures_directory');
        $url = $urlPattern."/".$lien;
        $response = new BinaryFileResponse($url);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,$lien);
        return $response;
    }

     /**
     * @Route("/admin/supprimer-cours/{id}", name="supprimercours")
     */
    public function supprimerfiliere(Request $request,$id)
    {
       
        $filesystem = new Filesystem();
       
       
 
        $repository = $this->getDoctrine()->getRepository(Cours::class);
        $cours = $repository->find($id);   
        $path= $this->getParameter('brochures_directory')."/".$cours->getLien();
        $message = "";
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cours);
            $em->flush();
            $filesystem->remove($path);
         
            // redirection
            return $this->redirectToRoute('listcours');
          }

        return $this->render('cours/supprimercours.html.twig', [
            'message'=>$message,
        ]);
    }

     /**
     * @Route("/admin/modifier-cours/{id}", name="modifiercours")
     */
    public function modifiercours(Request $request,$id)
    {
        $repository = $this->getDoctrine()->getRepository(Cours::class);
        $cours = $repository->find($id);    
        $message = "";
        if ($request->isMethod('POST')) {
            $titre = $request->request->get("titre");
            $description = $request->request->get("description");
            $cours->setTitre($titre);
            $cours->setDescription($description);
            $em = $this->getDoctrine()->getManager();
            $em->persist($cours);
            $em->flush();
            $message = "Enregistrement effectué avec succes";
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
          }

        return $this->render('cours/modifiercours.html.twig', [
            'cours'=>$cours,
            'message'=>$message,
        ]);
    }

    /**
     * @Route("/admin/modifier-fichier/{id}", name="modifierfichier")
     */
    public function modifierfichier(Request $request,$id)
    {
        $repository = $this->getDoctrine()->getRepository(Cours::class);
        $cours = $repository->find($id);    
        $message = "";
        $form   = $this->get('form.factory')->create(FichierType::class, $cours);    
        $message = "";
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                      /** @var UploadedFile $brochureFile */
                      $brochureFile = $form['brochure']->getData();
                      if ($brochureFile) {

                        $filesystem = new Filesystem();
                        $path= $this->getParameter('brochures_directory')."/".$cours->getLien();
                        $filesystem->remove($path);

                        $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                        // this is needed to safely include the file name as part of the URL
                        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
        
                        // Move the file to the directory where brochures are stored
                        try {
                            $brochureFile->move(
                                $this->getParameter('brochures_directory'),
                                $newFilename
                            );
                        } catch (FileException $e) {
                            // ... handle exception if something happens during file upload
                        }
        
                        // updates the 'brochureFilename' property to store the PDF file name
                        // instead of its contents
                        $cours->setLien($newFilename);
                        $cours->setDatepub(new \DateTime());
                        
                    }

                    // persiste cours
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($cours);
                    $em->flush();
                    $message = "Fichier changer  avec succes";
                    $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
                    $cours = new Cours();
                    $form   = $this->get('form.factory')->create(FichierType::class, $cours);  

          }

        return $this->render('cours/modifierfichier.html.twig', [
            'form'=>$form->createView(),
            'message'=>$message,
        ]);
    }

    /**
     * @Route("/creeradmin", name="creeradmin")
     */
    public function creerAdmin(UserPasswordEncoderInterface $encoder){
        $user = new User();
        $user->setUsername("alain@gmail.com");
        $user->setEmail("alain@gmail.com");
        $plainPassword = "alain";
        $encoded = $encoder->encodePassword($user, $plainPassword);
        $user->setPassword($encoded);
        $roles[] = 'ROLE_ADMIN';
        $user->setRoles($roles);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
    }
}
