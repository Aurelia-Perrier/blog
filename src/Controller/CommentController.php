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
     * @Route ("comment/{id}/validate",name="app_comment_validate", requirements={"id"="\d+"}, methods={"POST"} )
     */
    public function validate(Comment $comment, Request $request, EntityManagerInterface $entityManager )
    {
        $submittedToken = $request->request->get('token');
        
        if ($this->isCsrfTokenValid('validate-comment', $submittedToken)) {
            $comment->setIsValidated(true);

            $post = $comment->getPost();

            $entityManager->flush($comment);

            return $this->redirectToRoute('app_single_post', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);

        }

    }

}