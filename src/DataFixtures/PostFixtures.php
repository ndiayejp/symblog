<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
            $faker = \Faker\Factory::create('fr_FR');

            // creer 3 categories faker
            for ($i=1; $i < 3; $i++) { 
                $category = new Category();
                $category->setName($faker->sentence())
                        ->setBody($faker->paragraph()); 
                        $manager->persist($category);

            // creer entre 3 et 4 articles pour chaque categories
            for ($j=1; $j <= mt_rand(4,6); $j++) { 
                $post = new Post();
                $content = '<p>'. join($faker->paragraphs(),'<p></p>') .'</p>';
                $post->setName($faker->sentence())
                     ->setContent($content)
                     ->setThumbnail($faker->imageUrl())
                     ->setCreatedAt($faker->dateTimeBetween('- 6 months'))
                     ->setCategory($category);
   
                    $manager->persist($post);

                for ($k=1; $k <= mt_rand(4,6); $k++) { 
                     $comment = new Comment();
                     $content = '<p>'. join($faker->paragraphs(),'<p></p>') .'</p>';
                     // creer une date compris entre la date de création de l'article et la date du jour
                     $days = (new \DateTime())->diff($post->getCreatedAt())->days;
                     $comment->setAuthor($faker->name)
                          ->setContent($content)
                          ->setCreatedAt($faker->dateTimeBetween('-'.$days.'days'))
                          ->setPost($post);

                          $manager->persist($comment);
                }
           }

        }

         

       

        $manager->flush();
    }
}
