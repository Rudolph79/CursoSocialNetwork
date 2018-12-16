<?php

namespace AppBundle\Controller;

use MyNetwork\BackendBundle\Entity\Publications;
use MyNetwork\BackendBundle\Form\PublicationsType;
use MyNetwork\BackendBundle\MyNetworkBackendBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use MyNetwork\BackendBundle\Entity\Following;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class PublicationController extends Controller
{
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $publication = new Publications();
        $form = $this->createForm(PublicationsType::class, $publication);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if($form->isValid()) {
                // upload image
                /*$file = $form['image']->getData();
                if (!empty($file) && $file != null) {
                    $ext = $file->guessExtension();
                    if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                        $file_name = $user->getId().time().".".$ext;
                        $file->move("upload/publications/images", $file_name);
                        $publication->setImage($file_name);
                    } else {
                        $publication->setImage(null);
                    }
                } else {
                    $publication->setImage(null);
                }*/
                // upload file
                $file = $form["image"]->getData();

                if (!empty($file) && $file != null) {
                    $ext = $file->guessExtension();
                    if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                        $file_name = $user->getId().time().'.'.$ext;
                        $file->move("upload/images", $file_name);

                        $publication->setImage($file_name);
                    }
                } else {
                    $publication->setImage(null);
                }

                // upload document
                /*$doc = $form['document']->getData();
                if (!empty($doc) && $doc != null) {
                    $ext = $file->guessExtension();
                    if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                        $file_name = $user->getId().time().".".$ext;
                        $doc->move("upload/publications/documents", $file_name);
                        $publication->setDocument($file_name);
                    } else {
                        $publication->setDocument(null);
                    }
                } else {
                    $publication->setDocument(null);
                }*/
                $doc = $form["document"]->getData();

                if (!empty($doc) && $doc != null) {
                    $ext = $doc->guessExtension();
                    //if ($ext == 'pdf' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                    if ($ext == 'pdf') {
                        $file_name = $user->getId().time().'.'.$ext;
                        $doc->move("upload/document", $file_name);

                        $publication->setDocument($file_name);
                    }
                } else {
                    $publication->setDocument(null);
                }

                $publication->setUser($user);
                $publication->setCreatedAt(new \DateTime("now"));

                $em->persist($publication);
                $flush = $em->flush();
                if ($flush == null) {
                    $status = "The publication has been created";
                } else {
                    $status = "Error, something is going wrong";
                }

            } else {
                $status = 'The publication can\'t be created';
            }

            $this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute('home_publications');
        }

        $publications = $this->getPublication($request);

        return $this->render('AppBundle:Publication:home.html.twig', [
            'form' => $form->createView(),
            'pagination' => $publications
        ]);
    }

    public function getPublication($request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $publication_repo = $em->getRepository('MyNetworkBackendBundle:Publications');
        $following_repo = $em->getRepository('MyNetworkBackendBundle:Following');

        $following = $following_repo->findBy(['user' => $user]);

        $following_array = [];

        foreach ($following as $follow) {
            $following_array[] = $follow->getFollowed();
        }

        $query = $publication_repo->createQueryBuilder('p')
            ->where('p.user = (:user_id) OR p.user IN (:following)')
            ->setParameter('user_id', $user->getId())
            ->setParameter('following', $following_array)
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $pagination;
    }

    public function removePublicationAction(Request $request, $id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $publication_repo = $em->getRepository('MyNetworkBackendBundle:Publications');
        $publication = $publication_repo->find($id);
        $user = $this->getUser();

        if ($user->getId() == $publication->getUser()->getId()) {
            $em->remove($publication);
            $flush = $em->flush();

            if ($flush == null) {
                $status = 'The publication has been created';
            } else {
                $status = 'Error, something is going wrong';
            }
        } else {
            $status = 'Error, something is going wrong';
        }

        return new Response($status);
    }
}
