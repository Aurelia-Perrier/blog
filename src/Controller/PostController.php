<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
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
       return $this->render('posts.html.twig', [
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
        return $this->render('post.html.twig', [
            'post' => $post
        ]);
    }

}