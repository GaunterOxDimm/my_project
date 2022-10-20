<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;

class ArticleController extends AbstractController
{

    #[Route('/article/all', name: 'app_article')]
    public function index()
    {
        $articles = [new Article('Orange'), new Article('Pomme'), new Article('Banane')];
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }



    #[Route('/article/{id}', name: 'article_getOne')]

    public function getArticle(ManagerRegistry $doctrine, int $id): Response
    {
        try {
            $article = $doctrine->getRepository(Article::class)->find($id);

            if (!$article) {
                throw $this->createNotFoundException(
                    'No product found for id ' . $id
                );
            }
            // $show = 'Check out this great article: ' . $article->getLibelle();

            return $this->render('article/detail.html.twig', ['article' => $article]);

            // or render a template
            // in the template, print things with {{ product.name }}
            // return $this->render('product/show.html.twig', ['product' => $product]);

        } catch (\ErrorException $e) {
            return $this->render('article/index.html.twig', [
                'controller_name' => 'ArticleController',
                'error' => $e->getMessage(),
            ]);
        }
    }
    #[Route('/fill', name: 'new_article')]

    public function new(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $article = new Article('Keyboard');
        $article->setLibelle('Keyboard');
        $article1 = new Article('Mouse');

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($article);
        $entityManager->persist($article1);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new articles with id ' . $article->getId() . ' & ' . $article1->getId());
    }
}
