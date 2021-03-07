<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(BlogRepository $blogRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $blogEnt = $em->getRepository(Blog::class);
        $latestBlogQuerry = $blogEnt->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'post' => $latestBlogQuerry,
        ]);
    }
}
