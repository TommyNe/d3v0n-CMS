<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Repository\AbouthRepository;
use App\Repository\BlogRepository;
use InstagramScraper\Instagram;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(BlogRepository $blogRepository, AbouthRepository $abouthRepository): Response
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

        $instagram = new Instagram(new \GuzzleHttp\Client());
        $insta_response = $instagram->getMedias('tommy080584');
        $insta = $insta_response[0]->getImageStandardResolutionUrl();





        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'post' => $latestBlogQuerry,
            'abouth' => $abouth,
            'instagram' => $insta_response,
        ]);

    }
}
