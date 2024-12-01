<?php

namespace App\Controller\Other;

use App\Entity\PlaylistSubscription;
use App\Repository\PlaylistRepository;
use App\Repository\PlaylistSubscriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
#[Route(path: '/playlists', name: 'page_playlists')]
public function list(PlaylistRepository $playlistRepository, PlaylistSubscriptionRepository $playlistSubscription): Response
{
    //recuperer toutes les playlists : un tableau d'objets
    $myPlaylistsSub = $playlistSubscription->findAll();
    $playlists = $playlistRepository->findAll();
    return $this->render('other/lists.html.twig', ['playlists' => $playlists, 'myPlaylistsSub' => $myPlaylistsSub]);
}
}