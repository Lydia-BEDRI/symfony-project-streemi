<?php

namespace App\Controller\Other;

use App\Entity\Media;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/movies', name: 'movies_')]
class MovieController extends AbstractController
{
    #[Route('/detail/{id}', name: 'detail', methods: ['GET'])]
    public function detail(Media $media): Response
    {
        return $this->render('movie/detail.html.twig', [
            'media' => $media
        ]);
    }

    #[Route('/detail/series/{id}', name: 'detail_serie', methods: ['GET'])]
    public function detailSerie(Media $media): Response
    {
        return $this->render('movie/detail_serie.html.twig', [
            'media' => $media,
        ]);
    }
}
