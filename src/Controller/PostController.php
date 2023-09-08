<?php

namespace App\Controller;

use DateTime;
use App\Entity\Post;
use App\Form\PostType;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{

    /**
     * Affiche tous les posts
     * Tri les posts en fonction de leurs catégories
     *
     * @Route ("/posts", name="app_posts", methods={"GET", "POST"})
     */
    public function posts(PostRepository $postRepository, CategoryRepository $categoryRepository, Request $request)
    {

        // on récupère l'id transmis lors de la souission du formulaire de tri
        $categoryId = $request->request->get('category');

        // si categoryId existe et qu'il est différent de 0
        if (isset($categoryId) && $categoryId != 0) {

            // on regarde sur chaque post s'ils possèdent la catégorie qui est demandé grâce à l'id envoyé dans categoryId
            // chaque post ayant la categorie correspondante : on le met dans un nouveau tableau
            $postsToSort = $postRepository->findAll();

            $posts = [];

            foreach ($postsToSort as $post) {

                $postCategory = $post->getCategory()->getValues();

                foreach ($postCategory as $category) {

                    if ($category->getId() == $categoryId) {
                        $posts[] = $post;
                    }
                }
            }
            // si categoryId n'existe pas ou qu'il est égal à 0
        } else if (!isset($categoryId) || $categoryId == 0) {

            // on recupère tous les posts
            $posts = $postRepository->findBy([], ['publishedAt' => 'DESC']);
        }

        // on envoie les posts, les categories et le formulaire dans le template
        $categories = $categoryRepository->findAll();
        return $this->render('post/posts.html.twig', [
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }

    /**
     *
     * Affiche un post
     * Traitement du formulaire pour l'ajout d'un commentaire
     * 
     * @Route ("/post/{id}", name="app_single_post", requirements={"id"="\d+"}, methods={"GET", "POST"})
     * 
     */
    public function post(PostRepository $postRepository, CommentRepository $commentRepository, Request $request, $id)
    {
        // création d'une instance de Comment
        $comment = new Comment();

        // création du formulaire
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        // si le formulaire est soumit
        if ($form->isSubmitted() && $form->isValid()) {

            // on set toutes les valeurs nécessaires et on lie le commentaire au post
            $postToBind = $postRepository->findOneBy(['id' => $id]);
            $comment->setPost($postToBind);
            $comment->setPublishedAt(new DateTime());
            $comment->setIsValidated(false);
            $comment->setIsRefused(false);

            // on envoit le nouveau commentaire en base de données
            $commentRepository->add($comment, true);

            //un message confirmant la création du commentaire
            $this->addFlash('success', 'Le commentaire est en ligne ! Merci pour votre contribution ');

            // redirection
            return $this->redirectToRoute('app_single_post', ['id' => $id], Response::HTTP_SEE_OTHER);
        }


        // affichage du post et du formulaire
        $post = $postRepository->findOneBy(['id' => $id]);
        return $this->renderForm('post/post.html.twig', [
            'post' => $post,
            'form' => $form
        ]);
    }

    /**
     * Affiche et traitement du formulaire d'ajout d'un post
     *
     * @Route ("/post/new", name="app_new_post", methods={"GET", "POST"})
     */
    public function new(Request $request, PostRepository $postRepository)
    {
        // refus si on n'est pas authentifié
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        //création d'une nouvelle instance de Post
        $post = new Post();

        //création d'un formulaire
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        // si le formulaire est soumit
        if ($form->isSubmitted() && $form->isValid()) {

            // set des champs et association à l'utilisateur connecté
            /** @var \App\Entity\User $user */
            $user = $this->getUser();
            $post->setPublishedAt(new DateTime());
            $post->setAuthor($user);
            $postRepository->add($post, true);

            // message de succès
            $this->addFlash('success', 'Le poste est en ligne ! Merci pour votre contribution ');


            // redirection
            return $this->redirectToRoute('app_posts', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/new.html.twig', [
            'form' => $form,
            'post' => $post
        ]);
    }
}
