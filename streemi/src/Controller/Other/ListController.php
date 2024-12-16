<?php
namespace App\Controller\Other;

use App\Entity\User;
use App\Repository\PlaylistRepository;
use App\Repository\PlaylistMediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ListController extends AbstractController
{
    #[Route(path: '/playlists', name: 'page_playlists')]
    #[IsGranted("ROLE_USER")] // Ajouter l'annotation pour restreindre l'accès aux utilisateurs connectés
    public function list(
        PlaylistRepository $playlistRepository,
        PlaylistMediaRepository $playlistMediaRepository,
        Request $request
    ): Response {
        /** @var User|null $currentUser */
        $currentUser = $this->getUser();

        if (!$currentUser) {
            return $this->redirectToRoute('home.index');
        }

        // Récupérer les playlists de l'utilisateur
        $playlists = $currentUser->getPlaylistSubscriptions();

        // Vérifier s'il y a des playlists disponibles
        if (!$playlists || count($playlists) === 0) {
            return $this->render('other/lists.html.twig', [
                'playlists' => [],
                'selectedPlaylist' => null,
                'medias' => [],
            ]);
        }

        // Vérifier si un paramètre 'selectedPlaylist' est présent dans l'URL
        $selectedPlaylistId = $request->query->get('selectedPlaylist');
        $selectedPlaylist = null;
        $medias = [];

        if ($selectedPlaylistId) {
            // Trouver la playlist correspondante
            $selectedPlaylist = $playlistRepository->find($selectedPlaylistId);
        }

        // Si aucune playlist sélectionnée, choisir la première
        if (!$selectedPlaylist) {
            $selectedPlaylist = $playlists[0]->getPlaylist();
        }

        // Récupérer les médias associés à la playlist sélectionnée
        if ($selectedPlaylist) {
            $playlistMedia = $playlistMediaRepository->findBy(['playlist' => $selectedPlaylist]);
            foreach ($playlistMedia as $entry) {
                $medias[] = $entry->getMedia();
            }
        }

        return $this->render('other/lists.html.twig', [
            'playlists' => $playlists,
            'selectedPlaylist' => $selectedPlaylist,
            'medias' => $medias,
        ]);
    }
}
