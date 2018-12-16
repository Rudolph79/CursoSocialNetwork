<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use MyNetwork\BackendBundle\Entity\Users;
use AppBundle\Form\RegisterType;
use MyNetwork\BackendBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class UserController extends Controller
{
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function loginAction(Request $request)
    {
        if (is_object($this->getUser())) {
            return $this->redirect('home');
        }

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('AppBundle:User:login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    public function registerAction(Request $request)
    {
        if (is_object($this->getUser())) {
            return $this->redirect('home');
        }

        $user = new Users();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()){
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();

                $query = $em->createQuery('SELECT u FROM MyNetworkBackendBundle:Users u WHERE u.email = :email OR u.nick = :nick')
                    ->setParameter('email', $form->get('email')->getData())
                    ->setParameter('nick', $form->get('nick')->getData());

                $user_isset = $query->getResult();

                if (count($user_isset) == 0){
                    $factory = $this->get("security.encoder_factory");
                    $encoder = $factory->getEncoder($user);

                    $password = $encoder->encodePassword($form->get("password")->getData(), $user->getSalt());

                    $user->setPassword($password);
                    $user->setRole("ROLE_USER");
                    $user->setImage(null);

                    $em->persist($user);
                    $flush = $em->flush();

                    if ($flush == null){
                        $status = "The creation of your account has been done successfully";

                        $this->session->getFlashBag()->add("status", $status);

                        return $this->redirect("login");
                    } else {
                        $status = "Something  is going wrong";
                    }
                } else {
                    $status = "This user already exists";
                }
            } else {
                $status = "Something is going wrong !!";
            }

            $this->session->getFlashBag()->add("status", $status);
        }

        return $this->render('AppBundle:User:register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function nickTestAction(Request $request)
    {
        $nick = $request->get("nick");

        $em = $this->getDoctrine()->getManager();
        $user_repo = $em->getRepository("MyNetworkBackendBundle:Users");
        $user_isset = $user_repo->findOneBy(["nick" => $nick]);

        $result = "used";
        if (count($user_isset) >= 1 && is_object($user_isset)){
            $result = "used";
        } else {
            $result = "unsed";
        }

        return new Response($result);
    }

    public function editUserAction(Request $request)
    {
        $user = $this->getUser();
        $user_image = $user->getImage();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()){
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();

                $query = $em->createQuery('SELECT u FROM MyNetworkBackendBundle:Users u WHERE u.email = :email OR u.nick = :nick')
                    ->setParameter('email', $form->get('email')->getData())
                    ->setParameter('nick', $form->get('nick')->getData());

                $user_isset = $query->getResult();

                if (count($user_isset) == 0 || ($user->getEmail() == $user_isset[0]->getEmail() && $user->getNick() == $user_isset[0]->getNick())){

                    // upload file
                    $file = $form["image"]->getData();

                    if (!empty($file) && $file != null) {
                        $ext = $file->guessExtension();
                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                            $file_name = $user->getId().time().'.'.$ext;
                            $file->move("upload/users", $file_name);

                            $user->setImage($file_name);
                        }
                    } else {
                        $user->setImage($user_image);
                    }
                    $em->persist($user);
                    $flush = $em->flush();

                    if ($flush == null){
                        $status = "The changes were made with success";
                    } else {
                        $status = "The changes went wrong";
                    }
                } else {
                    $status = "This user already exists";
                }
            } else {
                $status = "The update did not go well !!";
            }

            $this->session->getFlashBag()->add("status", $status);
        }

        return $this->render('AppBundle:User:edit_user.html.twig', [
            "form" => $form->createView()
        ]);
    }

    public function usersAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT u FROM MyNetworkBackendBundle:Users u ORDER BY u.id ASC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, $request->query->getInt('page', 1), 5
        );

        return $this->render('AppBundle:User:users.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /*public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $search = $request->query->get("search", null);

        if ($search == null) {
            return $this->redirect($this->generateUrl('users_list'));
        }

        $dql = "SELECT u FROM MyNetworkBackendBundle:Users u "
            . "WHERE u.name LIKE :search OR u.username LIKE :search "
            . "OR u.nick LIKE :search ORDER BY u.id ASC";
        $query = $em->createQuery($dql)->setParameter('search', "%search%");


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, $request->query->getInt('page', 1), 5
        );

        dump($pagination);die;

        return $this->render('AppBundle:User:users.html.twig', [
            'pagination' => $pagination,
        ]);
    }*/

    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $search = trim($request->query->get("search", null));

        if ($search == null) {
            return $this->redirect($this->generateUrl('users_list'));
        }

        /*$dql = "SELECT u FROM MyNetworkBackendBundle:Users u "
                . "WHERE u.name LIKE :search OR u.surname LIKE :search "
                . "OR u.nick LIKE :search ORDER BY u.id ASC";*/
        $dql = "SELECT u FROM MyNetworkBackendBundle:Users u WHERE u.name LIKE :search OR u.surname LIKE :search OR u.nick LIKE :search ORDER BY u.id ASC";
        $query = $em->createQuery($dql)->setParameter('search', "%$search%");
        //dump($query);die;

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, $request->query->getInt('page', 1), 5);
        //dump($pagination);die;

        return $this->render('AppBundle:User:users.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
