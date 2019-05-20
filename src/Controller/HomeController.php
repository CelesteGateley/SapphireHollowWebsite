<?php
// src/Controller/HomeController
namespace App\Controller;
use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {

    /**
     * @Route("/")
     * @Route("/index")
     * @Route("/home")
     */
    public function home() {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render('pages/home.html.twig', array(
            'page_title' => 'Homepage',
            'articles' => $articles
            ));
    }
 }
