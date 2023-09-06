<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    /**
     * Undocumented function
     *
     * @Route ("{id}/validate",name="app_comment_validate", requirements={"id"="\d+"}, methods={"POST"} )
     */
    public function validate(Comment $comment, Request $request, EntityManagerInterface $entityManager )
    {
        $post = $comment->getPost();

        $this->denyAccessUnlessGranted('comment_validate', $post);
        $submittedToken = $request->request->get('token');
        
        if ($this->isCsrfTokenValid('validate-comment', $submittedToken)) {

            if($comment->isIsRefused() === true){
                $comment->setIsRefused(false);
            }
            $comment->setIsValidated(true);


            $entityManager->flush($comment);

            return $this->redirectToRoute('app_single_post', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);

        }

    }

    /**
     * Undocumented function
     *
     * @Route ("{id}/refuse",name="app_comment_refuse", requirements={"id"="\d+"}, methods={"POST"} )
     */
    public function refuse(Comment $comment, Request $request, EntityManagerInterface $entityManager )
    {
        $post = $comment->getPost();
        $this->denyAccessUnlessGranted('comment_refuse', $post);
        $submittedToken = $request->request->get('token');
        
        if ($this->isCsrfTokenValid('refuse-comment', $submittedToken)) {
            
            if ($comment->isIsValidated() === true){
                $comment->setIsValidated(false);
            }
            $comment->setIsRefused(true);

            $entityManager->flush($comment);

            return $this->redirectToRoute('app_single_post', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);

        }

    }

}