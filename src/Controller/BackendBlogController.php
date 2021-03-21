<?php

namespace App\Controller;


use App\Entity\Abouth;
use App\Entity\Blog;
use App\Form\AbouthType;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend", name="backend.")
 */
class BackendBlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(BlogRepository $blog,  Request $request, PaginatorInterface $paginator): Response
    {
       $em = $this->getDoctrine()->getManager();
       $blogRepository = $em->getRepository(Blog::class);
        $allBlogQuerry = $blogRepository->createQueryBuilder('p')
            ->where('p.id != :id')
            ->setParameter('id', 'canceled')
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        $blg = $paginator->paginate(
            $allBlogQuerry,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('backend_blog/index.html.twig', [
            'blog' => $blg,
        ]);
    }
    /**
     * @Route("/entfernen/{id}", name="entfernen")
     */
    public function entfernen($id, BlogRepository $blg){
        $em = $this->getDoctrine()->getManager();
        $blog = $blg->find($id);
        $em->remove($blog);
        $em->flush();
        return $this->redirect($this->generateUrl('backend.blog'));
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Blog $blog, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($blog);
            $em->flush();
            $this->addFlash('success', 'Article Updated! Inaccuracies squashed!');
            return $this->redirectToRoute('backend.blog', [
                'id' => $blog->getId(),
            ]);
        }
        return $this->render('backend_blog/edit.html.twig', [
            'edit' => $form->createView()
        ]);
    }

    /**
     * @Route("/abouth/{id}", name="abouth")
     */
    public function abouth(Abouth $abouth, Request $request)
    {


        $form = $this->createForm(AbouthType::class, $abouth);
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();

            $pict = $request->files->get('abouth') ['picture'];


            if ($pict) {
                $picname = md5(uniqid()) . '.' . $pict->guessClientExtension();
            }
            $pict->move(
                $this->getParameter('bilder_ordner'),
                $picname
            );

            $abouth->setPicture($picname);
            $em->persist($abouth);
            $em->flush();
            $this->addFlash('success', 'Article Updated! Inaccuracies squashed!');
            return $this->redirectToRoute('backend.abouth',
                [
                    'id' => $abouth->getId(),
                ]
            );

        }

        return $this->render('backend_blog/abouth.html.twig', [
            'abouth' => $form->createView()
        ]);
    }

}
