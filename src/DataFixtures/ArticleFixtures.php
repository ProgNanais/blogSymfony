<?php

namespace App\DataFixtures;

use App\Entity\Articles;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create('fr_FR');

        // Créer 3 catégories fakées
        for($i = 1; $i <=3; $i ++) {
            $category = new Category();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());
            
            $manager->persist($category);

            // Créer entre 4 et 6 articles
            for($j = 1; $j <= mt_rand(4, 6); $j++) {
                $article = new Articles();

                $article->setTitle($faker->sentence())
                        ->setContent($faker->paragraph())
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTime())
                        ->setCategory($category);

                $manager->persist($article);

                // On donne des commentaires à l'article
                for($k = 1; $k <= mt_rand(4, 10); $k++) {
                    $comment = new Comment();

                    //$days = (new \DateTime())->diff($article->getCreatedAt())->days;

                    $comment->setAuthor($faker->name)
                            ->setContent($faker->paragraph())
                            ->setCreatedAt($faker->dateTime())
                            ->setArticle($article);
                    
                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
