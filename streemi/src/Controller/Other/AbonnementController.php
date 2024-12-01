<?php

namespace App\Controller\Other;

use App\Repository\SubscriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AbonnementController extends AbstractController
{
#[Route(path: '/subscriptions', name: 'page_subscription')]
    public function abonnement(SubscriptionRepository $subscriptionRepository): Response
    {
        //recuperer toutes les subscriptions : un tableau d'objets
        $subscriptions = $subscriptionRepository->findAll();
        return $this->render('other/abonnements.html.twig', ['subscriptions' => $subscriptions]);
}


}