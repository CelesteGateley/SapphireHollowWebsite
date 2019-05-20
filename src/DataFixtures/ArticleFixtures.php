<?php
// src/DataFixtures/ArticleFixtures
namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends Fixture {

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
       for ($i = 1; $i < 20; $i++) {
           $article = new Article();
           $article->setTitle('Article ' . $i);
           $val = '';
           for ($x = 0; $x < $i; $x++) { $val .= 'Test '; }
           $article->setBody($val);
           $manager->persist($article);
       }
       $manager->flush();
    }
}