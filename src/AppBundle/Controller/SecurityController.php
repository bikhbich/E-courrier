<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use AppBundle\Entity\Notifications;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {

        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('home');
        }

        return $this->render(
            'auth/login.html.twig',
            array(
                'last_username' => $authenticationUtils->getLastUsername(),
                'error'         => $authenticationUtils->getLastAuthenticationError(),
            )
        );
    }

    /**
     * @Route("/login_check", name="security_login_check")
     */
    public function loginCheckAction()
    {

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {

    }

    /**
     * @Route("/home", name="home")
     */
    public function homeAction()
    {
        /*$em = $this->getDoctrine()->getManager();
        $user= $this->get('security.token_storage')->getToken()->getUser();
        $notifications = $em->getRepository('AppBundle:Notifications')->findBy(array('user'=>$user));

        return $this->render('auth/home.html.twig',array('notifications'=>$notifications));*/
        return $this->render('auth/home.html.twig');
    }
}
