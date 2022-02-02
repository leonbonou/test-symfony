<?php

namespace App\Controller;

use App\Entity\UserClient;
use App\Form\RegistrationType;
use App\Notification\EmailNotification;
use App\Repository\UserClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @Route("/registration", name="security_registration")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param EmailNotification $notification
     * @return mixed
     */
    public function registration(Request $request, UserPasswordEncoderInterface $encoder, EmailNotification $notification)
    {
        $user_client = new UserClient();

        $form = $this->createForm(RegistrationType::class, $user_client);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user_client, $user_client->getPassword());
            $user_client->setPassword($hash);
            $user_client->setEmailToken(md5(uniqid()));
            $this->getDoctrine()->getManager()->persist($user_client);
            $this->getDoctrine()->getManager()->flush();

            $notification->confirmAccount($user_client, Swift_Mailer::class);

            return $this->redirectToRoute("email_confirmation");
        }

        return $this->render("security/registration.html.twig", [
            'form'  => $form->createView()
        ]);
    }

    /**
     * @Route("/connexion", name="security_login_client")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils) {
        $lastUsername=$authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render("security/connect.html.twig", [
            'last_username'  => $lastUsername,
            'error'          => $error
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     * @return RedirectResponse
     */
    public function logout() {
        $this->addFlash('success', 'Vous vous êtes bien déconnecté');
        return $this->redirectToRoute("home");
    }

    /**
     * @Route("/active-account/{token}", name="active_account")
     * @param String $token
     * @param UserClientRepository $userRepo
     * @param EntityManagerInterface $manager
     * @return RedirectResponse
     */
    public function activeAccount(String $token, UserClientRepository $userRepo, EntityManagerInterface $manager) {
        $user = $userRepo->findOneBy(['email_token'=>$token]);
        if(!$user) {
            throw $this->createNotFoundException("Cet utilisateur n'existe pas");
        }
        $user->setEmailToken(null);
        $manager->persist($user);
        $manager->flush();
        $this->addFlash('success', 'Compte activé avect succès');
        return $this->redirectToRoute('security_login_client');
    }

    /**
     * @Route("/email_confirmation", name="email_confirmation")
     * @return Response
     */
    public function accountsuccess() {
        return $this->render("account/activated.html.twig");
    }

    /**
     * @Route("/confirmation", name="email_error")
     * @return Response
     */
    public function errorpage() {
        return $this->render("account/noActive.html.twig");
    }
}
