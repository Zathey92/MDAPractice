<?php
namespace App\Controller;

use App\Entity\Doctors;
use App\Entity\Users;
use App\Form\DoctorType;
use App\Form\LoginType;
use App\Form\UserType;
use App\Repository\DoctorsRepository;
use App\Repository\UsersRepository;
use App\Services\IncidentsService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AppController extends Controller
{
    public function index()
    {
        return $this->render('index.html.twig');
    }

    public function login(AuthenticationUtils $authenticationUtils){
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginType::class, ['dni'=>$lastUsername]);
        if($error !=null){
            $form->addError(new FormError($error->getMessageKey()));
        }
        return $this->render('login.html.twig', array(
            'form' => $form->createView(),
            'error'=> $error,
        ));
    }

    public function addUser(Request $request, UsersRepository $ur, UserPasswordEncoderInterface $passwordEncoder)
    {
        $error = null;
        $user= new Users();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $error = $ur->addUser($user);

            if($error==null){
                return $this->redirectToRoute('index');
            }else{
                $form->addError(new FormError('Exception: '.$error));
            }
        }

        return $this->render('adduser.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function addDoctor(Request $request, DoctorsRepository $ur, UserPasswordEncoderInterface $passwordEncoder)
    {
        $error = null;
        $user= new Doctors();
        $form = $this->createForm(DoctorType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $error = $ur->addUser($user);

            if($error==null){
                return $this->redirectToRoute('index');
            }else{
                $form->addError(new FormError('Exception: '.$error));
            }
        }

        return $this->render('addDoctor.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function addIncident(Request $request, IncidentsService $is){
        $error = null;
        $email=$request->get('email');
        $description=$request->get('description');
        $userType=-1;
        if($this->isGranted('ROLE_DOCTOR')){
            $userType=1;
        }elseif($this->isGranted('ROLE_USER')){
            $userType=0;
        }

        if(isset($email,$description)){
            $error = $is->newIncident($email,$userType,$this->getUser(),$description);
            if($error==null){
                return $this->redirectToRoute('index');
            }
        }

        return $this->render('addIncident.html.twig');

    }
}