<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Comment;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use App\Repository\CommentRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
    public function index(BlogRepository $blog, Request $request, PaginatorInterface $paginator, CommentRepository $commentRepository): Response
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

        $q = $request->query->get('q');
        $search = $blog->findAllWithSearch($q);

        $blg = $paginator->paginate(
            $allBlogQuerry,
            $request->query->getInt('page', 1),
            5
        );


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


            $username = $this->getUser()->getUsername();


            $blog->setPic($picname);
            $blog->setUser($username);
            $blog->setDate(new \DateTime());
            $em->persist($blog);
            $em->flush();

            return $this->redirectToRoute('blog');
        }



        return $this->render('blog/create.html.twig', [
            'create' => $form->createView(),
        ]);
    }

    /**
     * @Route("/details/{id}", name="details")
     */
    public function details(BlogRepository $blogRepository, $id, Request $request, Blog $blog, CommentRepository $commentRepository)
    {

        $commentForm = $this->createFormBuilder()
            ->setMethod('GET')
            ->add('name', TextType::class, ['label' => 'Name *'])
            ->add('email', TextType::class, ['label' => 'Email *'])
            ->add('website', TextType::class, ['label' => 'Website'])
            ->add('content', TextareaType::class, ['label' => 'Content *'])
            ->add('post', SubmitType::class)
            ->getForm();

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();

            $comment = $commentForm->getData();

            $cmt = new Comment();

            $cmt->setDate(new \DateTime());
            $cmt->setEmail($comment['email']);
            $cmt->setName($comment['name']);
            $cmt->setWebsite($comment['website']);
            $cmt->setContent($comment['content']);
            $cmt->setBlog($blog);
            $em->persist($cmt);
            $em->flush();

            return $this->redirect($this->generateUrl('blog.details', ['id' => $id]));

        }


        $com = $commentRepository->findBy(['blog' => $id]);




        $latestBlogQuerry = $blogRepository->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();

        $q = $request->query->get('q');
        $search = $blogRepository->findAllWithSearch($q);

        $blg = $blogRepository->find($id);

        return $this->render('blog/details.html.twig', [
            'details' => $blg,
            'latest' => $latestBlogQuerry,
            'search' => $search,
            'coform' => $commentForm->createView(),
            'comment' => $com,
        ]);
    }


}


