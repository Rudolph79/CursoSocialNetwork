<?php

namespace AppBundle\Controller;

use MyNetwork\BackendBundle\Entity\Likes;
use MyNetwork\BackendBundle\Form\PublicationsType;
use MyNetwork\BackendBundle\MyNetworkBackendBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use MyNetwork\BackendBundle\Entity\Following;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class LikeController extends Controller
{
    public function likeAction($id = null)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $publication_repo = $em->getRepository('MyNetworkBackendBundle:Publications');
        $publication = $publication_repo->find($id);

        $like = new Likes();
        $like->setUser($user);
        $like->setPublication($publication);

        $em->persist($like);
        $flush = $em->flush();

        if ($flush == null) {
            $status = 'The Like is done';
        } else {
            $status = "You can\' like This publication";
        }

        return new Response($status);
    }

    public function unlikeAction($id = null)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $like_repo = $em->getRepository('MyNetworkBackendBundle:Likes');
        $like = $like_repo->findOneBy([
            'user' => $user,
            'publication' => $id,
        ]);

        $em->remove($like);
        $flush = $em->flush();

        if ($flush == null) {
            $status = 'The Unlike is done';
        } else {
            $status = "You can\' unlike This publication";
        }

        return new Response($status);
    }
}