<?php
// src/Controller/HomeController
namespace App\Controller;
use App\Entity\Article;
use App\Entity\User;
use DateTime;
use App\Form\ArticleFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController {

    private function generateAuthorsArray(array $articles) : array {
        $authors = [];
        foreach ($articles as $id => $article) {
            $id = $article->getAuthor();
            if (!isset($authors[$id])) {
                $authors[$id] = $this->getDoctrine()->getRepository(User::class)->find($article->getAuthor())->getUsername();
            }
        }
        return $authors;
    }

    /**
     * @Route("/", name="app_home")
     * @Route("/index")
     * @Route("/home")
     */
    public function homeScreen() {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findByOrderAsc();

        array_slice($articles, -3,3, true);

        return $this->render("pages/home.html.twig", array(
            'articles' => $articles,
            'authors' => $this->generateAuthorsArray($articles)
            ));
    }

    /**
     * @Route("/articles", name="app_articles")
     * @IsGranted("ROLE_ARTICLE_LIST")
     */
    public function allArticles() {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render("pages/articles.html.twig", array(
            'articles' => $articles,
            'authors' => $this->generateAuthorsArray($articles)
        ));
    }

    /**
     * @Route("/article/new", name="app_article_new")
     * @Route("/articles/new")
     * @IsGranted("ROLE_ARTICLE_CREATE")
     */
    public function newArticle(Request $request) {
        $article = new Article();

        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setTitle($form->get('title')->getData());
            $article->setBody($form->get('body')->getData());
            $article->setAuthor($this->getUser()->getId());
            $article->setCreatedOn(new DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('pages/articleeditor.html.twig', [
            'articleForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/article/edit/{id}", name="app_article_edit")
     * @IsGranted("ROLE_ARTICLE_EDIT")
     */
    public function editArticle(Request $request, string $id) {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        if (!isset($article)) { throw $this->createNotFoundException('Selected article does not exist'); }

        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setTitle($form->get('title')->getData());
            $article->setBody($form->get('body')->getData());
            $article->setAuthor($this->getUser()->getId());
            $article->setCreatedOn(new DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('pages/articleeditor.html.twig', [
            'articleForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/article/delete/{id}", name="app_article_delete")
     * @IsGranted("ROLE_ARTICLE_DELETE")
     * @param string $id
     * @return RedirectResponse
     */
    public function deleteArticle(string $id): RedirectResponse
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        if (!isset($article)) { throw $this->createNotFoundException('Selected article does not exist'); }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();
        return $this->redirectToRoute('app_articles');
    }

    /**
     * @Route("/article/{id}", name="app_article")
     * @param string $id
     * @return Response
     */
    public function viewArticle(string $id): Response
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        if (!isset($article)) { throw $this->createNotFoundException('Selected article does not exist'); }

        return $this->render('pages/article.html.twig', array(
            'article' => $article,
            'authors' => $this->generateAuthorsArray([$article, ])
        ));
    }

 }
