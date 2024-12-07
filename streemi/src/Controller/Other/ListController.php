<?php
namespace App\Controller\Other;

use App\Repository\PlaylistRepository;
use App\Repository\PlaylistMediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    #[Route(path: '/playlists', name: 'page_playlists')]
    public function list(
        PlaylistRepository $playlistRepository,
        PlaylistMediaRepository $playlistMediaRepository,
        Request $request
    ): Response {
        // Récupérer toutes les playlists
        $playlists = $playlistRepository->findAll();

        // Vérifier si un paramètre 'selectedPlaylist' est présent dans l'URL
        $selectedPlaylistId = $request->query->get('selectedPlaylist');
        $selectedPlaylist = null;
        $medias = [];

        if ($selectedPlaylistId) {
            // Trouver la playlist correspondante
            $selectedPlaylist = $playlistRepository->find($selectedPlaylistId);

            if ($selectedPlaylist) {
                // Récupérer les médias associés via PlaylistMedia
                $playlistMedia = $playlistMediaRepository->findBy(['playlist' => $selectedPlaylist]);
                foreach ($playlistMedia as $entry) {
                    $medias[] = $entry->getMedia(); // Ajoute le média à la liste
                }
            }
        }

        return $this->render('other/lists.html.twig', [
            'playlists' => $playlists,
            'selectedPlaylist' => $selectedPlaylist,
            'medias' => $medias,
        ]);
    }
}
