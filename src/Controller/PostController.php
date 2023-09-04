<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController {

    /**
    *
    * @Route ("/posts", name="app_posts")
    */
   public function posts(PostRepository $postRepository, CategoryRepository $categoryRepository)
   {
       $posts = $postRepository->findAll();
       $categories =$categoryRepository->findAll();
       return $this->render('post/posts.html.twig', [
           'posts' => $posts,
           'categories' => $categories,
       ]);
   }

    /**
     *
     * @Route ("/post/{id}", name="app_single_post", requirements={"id"="\d+"})
     */
    public function post(PostRepository $postRepository, $id)
    {
        $post = $postRepository->findOneBy(['id' => $id]);
        return $this->render('post/post.html.twig', [
            'post' => $post
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


            // redirection
            return $this->redirectToRoute('app_posts', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/new.html.twig', [
            'form' => $form,
            'post' => $post
        ]);
    }

}