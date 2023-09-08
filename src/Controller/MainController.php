<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     *Page d'accueil

     * @Route ("/", name="app_home")
     */
    public function index(PostRepository $postRepository)
    {
        $lastPosts = $postRepository->findBy([], ['publishedAt' => 'DESC'], 4);
        return $this->render('main.html.twig', [
            'lastPosts' => $lastPosts,
        ]);
    }

    /**
     *Page de contact

     * @Route ("/contact", name="app_contact")
     */
    public function contatc()
    {
        return $this->render('contact.html.twig', []);
    }
}