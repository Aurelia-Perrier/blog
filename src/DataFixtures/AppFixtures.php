<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    private $connection;
    // Injection od the DB Connection
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function load(ObjectManager $manager): void
    {
        // importing Faker
        $faker = Factory::create('fr_FR');

        //Creatin Users

        $userList = [];
        for ($u = 1; $u <= 10; $u ++ ) {

            $user = new User();
            $user->setEmail('user'.$u.'@gmail.com');
            $user->setPassword('$2y$13$9BlAyGpqFDo.5uCC99xTyOKAgu8HSU.oFYKetRDE6fJtrQtZlhZjq');
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setRoles(["ROLE_USER"]);

            $userList[] = $user;
            $manager->persist($user);
        }

        // creating categories

        $categoryList = [];
        for ($c = 1; $c <= 5; $c++) {

            $category = new Category();
            $category->setName($faker->colorName());
            
            $categoryList[] = $category;

            $manager->persist($category);
        }

        //creating posts

        $postsList = [];
        for ($p = 1; $p <= 10 ; $p++) {

            $post = new Post();
            $post->setTitle($faker->sentence());
            $post->setBody($faker->text(300));
            $post->setPublishedAt(new DateTime());
            $post->setAuthor($userList[mt_rand(0, count($userList) - 1)]);

            //adding 1 to 2 categories on each post
            for ($r = 1; $r <= mt_rand(1, 2); $r++) {

                $randomPost = $categoryList[mt_rand(0, count($categoryList) - 1)];
                $post->addCategory($randomPost);
                
            }

            $postsList[] = $post;

            $manager->persist($post);
        }

        //creating comments

        for($o = 1; $o <=15 ; $o++) {
            $comment = new Comment();
            $comment->setUsername($faker->userName());
            $comment->setBody($faker->text(150));
            $comment->setPublishedAt(new DateTime());
            $comment->setIsValidated(false);
            $comment->setIsRefused(false);
            $comment->setPost($postsList[mt_rand(0, count($postsList) - 1)]);

            $manager->persist($comment);
        }



        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
