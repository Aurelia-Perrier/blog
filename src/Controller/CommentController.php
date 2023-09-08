<?php

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    /**
     * Valide un commentaire
     *
     * @Route ("comment/{id}/validate",name="app_comment_validate", requirements={"id"="\d+"}, methods={"POST"} )
     */
    public function validate(Comment $comment, Request $request, EntityManagerInterface $entityManager )
    {
        // on recupère le post lié au commentaire
        $post = $comment->getPost();

        // on vérifie si l'utilisateur qui valide le commentaire est bien celui qui a publié le post
        $this->denyAccessUnlessGranted('comment_validate', $post);
        $submittedToken = $request->request->get('token');
        
        // on vérifie si le token CSRF est bien valide
        if ($this->isCsrfTokenValid('validate-comment', $submittedToken)) {

            // si le commentaire avait été refusé on modifie sa valeur afin de la passer à false
            if($comment->isIsRefused() === true){
                $comment->setIsRefused(false);
            }
            // on change la valeur de IsValidated
            $comment->setIsValidated(true);

            // on envoie les changements en base de données
            $entityManager->flush($comment);

            // on redirige sur la page du post ayant le commentaire fraichement validé
            return $this->redirectToRoute('app_single_post', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);

        }

    }

    /**
     * Refuse un commentaire
     *
     * @Route ("comment/{id}/refuse",name="app_comment_refuse", requirements={"id"="\d+"}, methods={"POST"} )
     */
    public function refuse(Comment $comment, Request $request, EntityManagerInterface $entityManager )
    {
        // on recupère le post lié au commentaire
        $post = $comment->getPost();

        // on vérifie si l'utilisateur qui valide le commentaire est bien celui qui a publié le post
        $this->denyAccessUnlessGranted('comment_refuse', $post);
        $submittedToken = $request->request->get('token');
        
        // on vérifie si le token CSRF est bien valide
        if ($this->isCsrfTokenValid('refuse-comment', $submittedToken)) {
            
            // si le commentaire avait été validé on modifie sa valeur afin de la passer à false
            if ($comment->isIsValidated() === true){
                $comment->setIsValidated(false);
            }

            // on change la valeur de IsRefused
            $comment->setIsRefused(true);

            // on envoie les changements en base de données
            $entityManager->flush($comment);

            // on redirige sur la page du post ayant le commentaire fraichement refusé
            return $this->redirectToRoute('app_single_post', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);

        }

    }

}