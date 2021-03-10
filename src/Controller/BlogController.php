<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog", name="blog.")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="view")
     */
    public function index(BlogRepository $blog, Request $request, PaginatorInterface $paginator): Response
    {
        $em = $this->getDoctrine()->getManager();

        $blogRepository = $em->getRepository(Blog::class);
        $allBlogQuerry = $blogRepository->createQueryBuilder('p')
            ->where('p.id != :id')
            ->setParameter('id', 'canceled')
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        $latestBlogQuerry = $blogRepository->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();

        $blg = $paginator->paginate(
            $allBlogQuerry,
            $request->query->getInt('page', 1),
            5
        );

        $q = $request->query->get('q');
        $search = $blog->findAllWithSearch($q);

        //$blg = $blog->findAll();

        return $this->render('blog/index.html.twig', [
            'blog' => $blg,
            'latest' => $latestBlogQuerry,
            'search' => $search,
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request) {
        $blog = new Blog();

        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $pict = $request->files->get('blog') ['bild'];


            if ($pict) {
                $picname = md5(uniqid()) . '.' . $pict->guessClientExtension();
            }
            $pict->move(
                $this->getParameter('bilder_ordner'),
                $picname
            );


            $blog->setPic($picname);
            $blog->setDate(new \DateTime());
            $em->persist($blog);
            $em->flush();

            return $this->redirectToRoute('blog');
        }



        return $this->render('blog/create.html.twig', [
            'create' => $form->createView(),
        ]);
    }
}
