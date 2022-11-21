<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticleType;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog')]
    public function index(ArticlesRepository $repo): Response
    {
        $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'title' => 'voici l\'index nommé "blog"',
            'articles' => $articles
        ]);
    }

    #[Route('/', name: 'home')]
    public function home() {
        return $this->render('blog/home.html.twig', [
            'title' => 'Bienvenue dans home!'
        ]);
    }

    #[Route('/blog/new', name: 'blog_create')]
    #[Route('/blog/{id}/edit', name: 'blog_edit')]
    public function form(Articles $article = null, Request $request, EntityManagerInterface $manager) {

        if(!$article) {
            $article = new Articles;
        }

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            if(!$article->getId()) {
                $article->setCreatedAt(new \DateTimeImmutable());
            }

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }

        return $this->render('blog/form.html.twig', [
            'title' => 'ho ! Tu veux créer un article ! (blog_create)',
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }

    #[Route('/blog/{id}', name: 'blog_show')]
    public function show(Articles $article) {

        return $this->render('blog/show.html.twig', [
            'title' => 'Bienvenue sur l\'article (show) !',
            'article' => $article
        ]);
    }
}
