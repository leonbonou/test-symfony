<?php


namespace App\Services;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class AuthService extends AbstractController
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var Environment
     */
    private $twig;

    /**
     * AuthVerifyService constructor.
     * @param Security $security
     * @param Environment $twig
     */
    public function __construct(Security $security, Environment $twig) {

        $this->security = $security;
        $this->twig = $twig;
    }

    public function verify () {
        $user = $this->security->getUser()->getEmailToken();

        if($user->$this->security->getUser()->getEmailToken()) {
            return $this->redirectToRoute('security_login_client');
        }
    }
}