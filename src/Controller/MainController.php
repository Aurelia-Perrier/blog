<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     *
     * @Route ("/", name="app_home")
     */
    public function index()
    {
        return $this->render('main.html.twig', []);
    }

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
     * @Route ("/posts", name="app_single_post")
     */
    public function posts()
    {
        return $this->render('posts.html.twig', []);
    }

    /**
     *
     * @Route ("/contact", name="app_contatc")
     */
    public function contatc()
    {
        return $this->render('contact.html.twig', []);
    }
}