<?php
// src/DataFixtures/ArticleFixtures
namespace App\DataFixtures;

use App\Entity\Article;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;

class ArticleFixtures extends Fixture {

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
       for ($i = 1; $i < 20; $i++) {
           $article = new Article();
           $article->setTitle('Article ' . $i);
           $article->setAuthor(1);
           $article->setCreatedOn(new DateTime());
           $val = '';
           for ($x = 0; $x < $i; $x++) { $val .= 'Test '; }
           $article->setBody($val);
           $manager->persist($article);
       }
       $manager->flush();
    }
}