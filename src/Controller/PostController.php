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
     *
     * @Route ("/posts", name="app_posts", methods={"GET", "POST"})
     */
    public function posts(PostRepository $postRepository, CategoryRepository $categoryRepository, Request $request)
    {

        $categoryId = $request->request->get('category');

        if (isset($categoryId) && $categoryId != 0) {

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
        } else if (!isset($categoryId) || $categoryId == 0) {

            $posts = $postRepository->findBy([], ['publishedAt' => 'DESC']);
            dump($posts);
        }

        $categories = $categoryRepository->findAll();
        return $this->render('post/posts.html.twig', [
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }

    /**
     *
     * @Route ("/post/{id}", name="app_single_post", requirements={"id"="\d+"}, methods={"GET", "POST"})
     * 
     */
    public function post(PostRepository $postRepository, CommentRepository $commentRepository, Request $request, $id)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        // if form is submitted
        if ($form->isSubmitted() && $form->isValid()) {

            $postToBind = $postRepository->findOneBy(['id' => $id]);
            $comment->setPost($postToBind);
            $comment->setPublishedAt(new DateTime());
            $comment->setIsValidated(false);
            $comment->setIsRefused(false);
            $commentRepository->add($comment, true);

            $this->addFlash('success', 'Le commentaire est en ligne ! Merci pour votre contribution ');

            // redirection
            return $this->redirectToRoute('app_single_post', ['id' => $id], Response::HTTP_SEE_OTHER);
        }


        $post = $postRepository->findOneBy(['id' => $id]);
        return $this->renderForm('post/post.html.twig', [
            'post' => $post,
            'comment' => $comment,
            'form' => $form
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @Route ("/post/new", name="app_new_post", methods={"GET", "POST"})
     */
    public function new(Request $request, PostRepository $postRepository)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $post = new Post();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        // if form is submitted
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var \App\Entity\User $user */
            $user = $this->getUser();
            $post->setPublishedAt(new DateTime());
            $post->setAuthor($user);
            $postRepository->add($post, true);

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
