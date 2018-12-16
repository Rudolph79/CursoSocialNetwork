<?php

namespace AppBundle\Controller;

use MyNetwork\BackendBundle\Entity\Following;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use MyNetwork\BackendBundle\Entity\Users;
use AppBundle\Form\RegisterType;
use MyNetwork\BackendBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class FollowingController extends Controller
{
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function followAction(Request $request)
    {
        $user = $this->getUser();
        $followed_id = $request->get('followed');

        $em = $this->getDoctrine()->getManager();

        $user_repo = $em->getRepository('MyNetworkBackendBundle:Users');
        $followed = $user_repo->find($followed_id);
        $following = new Following();
        $following->setUser($user);
        $following->setFollowed($followed);

        $em->persist($following);
        $flush = $em->flush();

        if ($flush == null) {
            $status = "Now you are following this user !!";
        } else {
            $status = "This user could not be followed !!";
        }

        return new Response($status);
    }

    public function unfollowAction(Request $request)
    {
        $user = $this->getUser();
        $followed_id = $request->get('followed');

        $em = $this->getDoctrine()->getManager();

        $following_repo = $em->getRepository('MyNetworkBackendBundle:Following');
        $followed = $following_repo->findOneBy([
            'user' => $user,
            'followed' => $followed_id,
        ]);

        $em->remove($followed);

        $flush = $em->flush();

        if ($flush == null) {
            $status = "Follow already this user !!";
        } else {
            $status = "You d'ont follow this user !!";
        }

        return new Response($status);
    }
}