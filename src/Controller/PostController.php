<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController {

    /**
     *
     * @Route ("/post", name="app_single_post")
     */
    public function post()
    {
        return $this->render('post.html.twig', []);
    }

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
}