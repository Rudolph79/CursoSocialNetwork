<?php

namespace MyNetwork\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users_repo = $em->getRepository("MyNetworkBackendBundle:Users");
        $user1 = $users_repo->findAll();

        //dump($user1);die;
        return $this->render('MyNetworkBackendBundle:Default:index.html.twig');
    }
}
