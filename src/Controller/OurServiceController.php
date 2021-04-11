<?php

namespace App\Controller;

use App\Entity\OurService;
use App\Form\OurServiceType;
use App\Repository\OurServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/our/service')]
class OurServiceController extends AbstractController
{
    #[Route('/', name: 'our_service_index', methods: ['GET'])]
    public function index(OurServiceRepository $ourServiceRepository): Response
    {
        return $this->render('our_service/index.html.twig', [
            'our_services' => $ourServiceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'our_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $ourService = new OurService();
        $form = $this->createForm(OurServiceType::class, $ourService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ourService);
            $entityManager->flush();

            return $this->redirectToRoute('our_service_index');
        }

        return $this->render('our_service/new.html.twig', [
            'our_service' => $ourService,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'our_service_show', methods: ['GET'])]
    public function show(OurService $ourService): Response
    {
        return $this->render('our_service/show.html.twig', [
            'our_service' => $ourService,
        ]);
    }

    #[Route('/{id}/edit', name: 'our_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OurService $ourService): Response
    {
        $form = $this->createForm(OurServiceType::class, $ourService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('our_service_index');
        }

        return $this->render('our_service/edit.html.twig', [
            'our_service' => $ourService,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'our_service_delete', methods: ['DELETE'])]
    public function delete(Request $request, OurService $ourService): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ourService->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ourService);
            $entityManager->flush();
        }

        return $this->redirectToRoute('our_service_index');
    }
}
