<?php

namespace App\Controller;
require_once 'c:/wamp64/www/pdfsoft/vendor/autoload.php';
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Universite;
use App\Entity\Filiere;
use App\Entity\Niveau;
use App\Entity\Student;
use App\Entity\User;
use App\Entity\Mail;
use App\Entity\Cours;



class StudentController extends AbstractController
{
    private $session;

    function __construct(SessionInterface $session){
        $this->session = $session;
    }
    /**
     * @Route("/student", name="student")
     */
    public function index()
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    /**
     * @Route("/student/mes-cours", name="mescours")
     */
    public function mescours()
    {
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository(Student::class);
        $students = $repository->findBy( ['user' => $user]); 
        $student = null;
        foreach ( $students as $etudiant){
            $student = $etudiant;
        }
        $filiere = $student->getFiliere();
        $niveau = $student->getNiveau();
        $repository = $this->getDoctrine()->getRepository(Cours::class);
       $cours = $repository->findBy(
            ['filiere' => $filiere ,'niveau' => $niveau]
            
        ); 
        return $this->render('student/mescours.html.twig', [
            'cours' => $cours,
        ]);
    }

    /**
     * @Route("/register/creer-un-compte-etudiant", name="studentregister")
     */
    public function register(Request $request,SessionInterface $session)
    {
        $error = null;
        

        if ($request->isMethod('POST')) {

           
            $nom = $request->request->get("nom");
            $prenom = $request->request->get("prenom");
            $email = $request->request->get("email");
            $telephone = $request->request->get("telephone");
             // check if email is free
            $repository = $this->getDoctrine()->getRepository(User::class);
            $student = $repository->findOneBy(['email' => $email]);

            if(!$student){

    
                $session->set("nom",$nom);
                $session->set("prenom",$prenom);
                $session->set("email",$email);
                $session->set("telephone",$telephone);
                return $this->redirectToRoute('studentregister2');
            }else{
                $error="Cet email est déjà utilisé, veuillez changer svp";
            }


        }

        $nom = $session->get("nom");
        $prenom = $session->get("prenom");
        $email = $session->get("email");
        $telephone = $session->get("telephone");

        return $this->render('student/register.html.twig', [
            'controller_name' => 'StudentController',
            'error'=>$error,
            'nom'=>$nom,
            'prenom'=>$prenom,
            'email'=>$email,
            'telephone'=>$telephone,
        ]);
    }
    /**
     * @Route("/register/register-etape-2", name="studentregister2")
     */
    public function register2(Request $request,SessionInterface $session)
    {
        $error = null;
        $repository = $this->getDoctrine()->getRepository(Filiere::class);
        $filieres = $repository->findAll();

        $repository = $this->getDoctrine()->getRepository(Universite::class);
        $universites = $repository->findAll();

        $repository = $this->getDoctrine()->getRepository(Niveau::class);
        $niveaux = $repository->findAll();
        $idfiliere=$session->get("filiere");
        $idniveau=$session->get("universite");
        $iduniversite=$session->get("niveau");
        if ($request->isMethod('POST')) {
            $filiere = $request->request->get("filiere");
            $universite = $request->request->get("universite");
            $niveau = $request->request->get("niveau");

            $session->set("filiere",$filiere);
            $session->set("universite",$universite);
            $session->set("niveau",$niveau);
            return $this->redirectToRoute('studentregister3');

        }

        return $this->render('student/register2.html.twig', [
            'controller_name' => 'StudentController',
            'error'=>$error,
            'filieres'=>$filieres,
            'universites'=>$universites,
            'idniveau'=>$idniveau,
            'idfiliere'=>$idfiliere,
            'iduniversite'=>$iduniversite,
            'niveaux'=>$niveaux,
        ]);
    }


        /**
     * @Route("/register/register-etape-3", name="studentregister3")
     */
    public function register3(Request $request,SessionInterface $session,UserPasswordEncoderInterface $encoder)
    {
        $error = null;
        $user = new User();
        if ($request->isMethod('POST')) {
            $password1 = $request->request->get("password1");
            $password2 = $request->request->get("password2");

            if($password1==$password2){

                // envois code de confirmation par email et mot de passe
                $code = $this->genererChaineAleatoire();
                $session->set("code",$code);
                
                $encoded = $encoder->encodePassword($user, $password1);
                $session->set("password",$encoded);

                $mail = new Mail();
                $titre = "Code de confirmation";
                $destinataire = "orelien.kamga@gmail.com";
                $message = "Votre code de confirmation PDF SOFT est: ".$code;
                //$mail->sendEmail($titre,$destinataire,$message);

                return $this->redirectToRoute('terminerregister');

            }else{
                $error="Les deux mots de passe ne correspondent pas";
            }
        }
        return $this->render('student/register3.html.twig', [
            'controller_name' => 'StudentController',
            'error'=>$error,
        ]);
    }
     /**
     * @Route("/secure/student/login", name="studentlogin")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
                // retrouver une erreur d'authentification s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
                // retrouver le dernier identifiant de connexion utilisé
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('student/login.html.twig', [
            'controller_name' => 'StudentController',
            'last_username' => $lastUsername,
            'error'=>$error,
        ]);
    }

    /**
     * @Route("/student/logout", name="security_logout_student")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Route("/register/confirmation-compte", name="terminerregister")
     */
    public function terminerregister(Request $request)
    {
        $error = null;
        $email = $this->session->get("email");
        $codeCurrent = $this->session->get("code");
        $telephone = $this->session->get("telephone");
        var_dump($codeCurrent);
        if ($request->isMethod('POST')) {
            $code = $request->request->get("code");
            if($code == $codeCurrent){
                    // inscription meme
                $student = new Student();
                $user = new User();
                $nom = $this->session->get("nom");
                $prenom = $this->session->get("prenom");
                $email = $this->session->get("email");
                $password = $this->session->get("password");
                $universite = $this->session->get("universite");
                $filiere = $this->session->get("filiere");
                $niveau = $this->session->get("niveau");
                $user->setNom($nom);
                $user->setPrenom($prenom);
                $user->setEmail($email);
                $user->setPassword($password);
                $user->setTelephone($telephone);
                $user->setUsername($email);
                //universite
                $repository = $this->getDoctrine()->getRepository(Universite::class);
                $universiteEntity = $repository->find($universite); 
               
                $student->setUniversite( $universiteEntity);

                //Niveau
                $repository = $this->getDoctrine()->getRepository(Niveau::class);
                $niveauEntity = $repository->find($niveau); 
                $student->setNiveau($niveauEntity); 

                // Filiere
                $repository = $this->getDoctrine()->getRepository(Filiere::class);
                $filiereEntity = $repository->find($filiere); 
                $student->setFiliere($filiereEntity); 

                $student->setDateinscription(new \DateTime());
                $student->setDatenaissance("");
                $student->setLieunaissance("");
                $student->setMatricule("");

                $roles[] = 'ROLE_USER';
                $user->setRoles($roles);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $student->setUser($user);
                $em->persist($student);
                $em->flush();


                // redirection vers la connexion
                $this->session->invalidate();
                $this->addFlash(
                    'notice',
                    'Votre compte a été crée. connectez vous pour demarer'
                );
                return $this->redirectToRoute('studentlogin');

             
            }else{
                $error="Code éronné";
            }
        }
        return $this->render('student/terminerregister.html.twig', [
            'controller_name' => 'StudentController',
            'error'=>$error,
            'email'=>$email,
            'telephone'=>$telephone,
        ]);
    }

    function genererChaineAleatoire()
    {
        $longueur = 5;
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $longueurMax = strlen($caracteres);
    $chaineAleatoire = '';
    for ($i = 0; $i < $longueur; $i++)
    {
    $chaineAleatoire .= $caracteres[rand(0, $longueurMax - 1)];
    }
    return $chaineAleatoire;
    }

    public function ConnectAction(Request $request, string $oauth)
    {
        $body = $request->query->all();
        $user = $this->container->get('oauth_manager')->logInUserWithOAuth($oauth, $body);

       if (!$user || $user === null) {
            return $this->redirectToRoute('homepage');
        }

      /** Handle getting or creating the user entity likely with a posted form */
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);
        $session = $request->getSession();
        $session = $this->get('session');
        $session->set('_security_main', serialize($token));

        /** Fire the login event manually */
        $event = new InteractiveLoginEvent($request, $token);
        $eventDispatcher = $this->get('event_dispatcher');
        $eventDispatcher->dispatch('security.interactive_login', $event);

        $url = 'dashboard';
        $response = new RedirectResponse($this->generateUrl($url));
        return $response;

    }


     /**
     * @Route("/student/tableaubord", name="studenttableaubord")
     */
    public function tableaubord()
    {
        $error = null;
        return $this->render('student/studenttableaubord.html.twig', [
            'controller_name' => 'StudentController',
            'error'=>$error,
        ]);
    }

     /**
     * @Route("/student/mes-cours", name="studentcours")
     */
    public function cours()
    {
        $error = null;
        return $this->render('student/cours.html.twig', [
            'controller_name' => 'StudentController',
            'error'=>$error,
        ]);
    }

   
}
