<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationFormType;
use App\Form\UserType;
use App\Repository\ParametreRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Message;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(ParametreRepository $parametreRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->appTel = $parametreRepository->findOneBy(array('nom' => 'telephone'))->getValeur();
        $this->appEmail = $parametreRepository->findOneBy(array('nom' => 'email'))->getValeur();
        $this->facebook = $parametreRepository->findOneBy(array('nom' => 'facebook'))->getValeur();
        $this->instagram = $parametreRepository->findOneBy(array('nom' => 'instagram'))->getValeur();
//BestBodyTravel.liveblog365.com is available
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error,
            'appTel' => $this->appTel, 'facebook' => $this->facebook,
            'instagram' => $this->instagram,

            'appEmail' => $this->appEmail,]);
    }

    /**
     *
     * @param Request $request
     * @return Response
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserRepository $userRepository, MailerInterface $mailer, ParametreRepository $parametreRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $exist = $userRepository->findOneBy(array('email' => $user->getEmail()));
            if ($exist) {
                $this->addFlash('danger', "l'adresse e-mail existe déjà");
            } else {
                $user->setPassword($this->passwordEncoder->encodePassword($user, $form->get("password")->getData()));
                $user->setUsername($form["username"]->getData());
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'success');
                $this->redirectToRoute('app_login');
            }
        }
        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
            'appTel' => $this->appTel,
            'appEmail' => $this->appEmail
        ]);
    }


    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
