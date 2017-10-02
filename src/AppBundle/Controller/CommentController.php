<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Event\CommentCreatedEvent;
use AppBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends Controller{
    /**
     * @Route("/create", name="comment_create")
     * @return Response
     */
    public function newAction(Request $request): Response{
        
        $em = $this->getDoctrine()->getManager();
        
        $comment= new Comment();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        $comment->setUser($user);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($comment);
            $em->getConnection()->beginTransaction();
            $em->flush();
            
            
            $this->get('event_dispatcher')->dispatch(CommentCreatedEvent::NAME, new CommentCreatedEvent($comment));
            
            // Déblocage du badge
            /*$comments_count = $em->getRepository('AppBundle:Comment')->countForUser($user->getId());
            
            $this->get('badge.manager')->checkAndUnlock($user, 'comment', $comments_count);*/
         
            $em->getConnection()->commit();
        }
         
        $comments= $em->getRepository('AppBundle:Comment')->findAll();
        $badges= $this->get('badge.manager')->getBadgeFor($user);
        
        
        return $this->render('comment/new.html.twig',[
            'comments' => $comments,
            'form' => $form->createView(),
            'badges' => $badges
        ]);
    }
            
}