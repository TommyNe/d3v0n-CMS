<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Repository\AbouthRepository;
use App\Repository\BlogRepository;
use App\Repository\OurServiceRepository;
use App\Repository\TeamRepository;
use InstagramScraper\Instagram;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(BlogRepository $blogRepository,
                          AbouthRepository $abouthRepository,
                          OurServiceRepository $ourServiceRepository,
                          TeamRepository $teamRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $blogEnt = $em->getRepository(Blog::class);
        $latestBlogQuerry = $blogEnt->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();

        $id = 1;

        $abouth = $abouthRepository->find($id);
        $service = $ourServiceRepository->findAll();
        $team = $teamRepository->findAll();


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'post' => $latestBlogQuerry,
            'abouth' => $abouth,
            'service' => $service,
            'team' => $team,

        ]);

    }
}
