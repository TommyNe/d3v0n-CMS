<?php

namespace App\Controller;

use App\Repository\DownloadsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DownloadController extends AbstractController
{
    #[Route('/download', name: 'download')]
    public function index(DownloadsRepository $downloadsRepository): Response
    {
        $dlink = $downloadsRepository->findAll();

        return $this->render('download/index.html.twig', [
            'link' => $dlink,
        ]);
    }
}
