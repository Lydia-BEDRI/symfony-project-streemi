<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\MediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private const POPULAR_MEDIA_LIMIT = 9;

    #[Route(path: '/', name: 'home.index')]
    public function homepage(MediaRepository $mediaRepository): Response
    {
        $medias = $mediaRepository->findPopular(self::POPULAR_MEDIA_LIMIT);

        if (empty($medias)) {
            $this->addFlash('info', 'Aucun média populaire trouvé.');
        }

        return $this->render('index.html.twig', [
            'medias' => $medias,
        ]);
    }
}
