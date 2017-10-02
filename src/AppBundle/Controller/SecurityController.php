<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Repository\UserRepository;


Class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @return Response 
     */
    public function loginAction(): Response{
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('security/login.html.twig',[
            'error'        => $error,
            'lastUsername' => $lastUsername
        ]);
    }
}

