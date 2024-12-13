<?php

namespace App\Controller\Other;

use App\Entity\User;
use App\Repository\SubscriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AbonnementController extends AbstractController
{
    #[Route(path: '/subscriptions', name: 'page_subscription')]
    public function abonnement(SubscriptionRepository $subscriptionRepository): Response
    {
        // Retrieve all subscriptions
        $subscriptions = $subscriptionRepository->findAll();

        // Get the current user
        /** @var User|null $currentUser */
        $currentUser = $this->getUser();

        // Get the current subscription if the user is authenticated
        $currentSubscription = $currentUser?->getCurrentSubscription();

        return $this->render('other/abonnements.html.twig', [
            'subscriptions' => $subscriptions,
            'subscription' => $currentSubscription,
        ]);
    }
}