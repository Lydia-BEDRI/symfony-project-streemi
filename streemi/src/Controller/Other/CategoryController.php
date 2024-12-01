<?php

declare(strict_types=1);

namespace App\Controller\Other;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    const POPULAR_MEDIA_LIMIT = 3;

    #[Route(path: '/category/{id}', name: 'page_category')]
    public function category(string $id, CategoryRepository $categoryRepository, MediaRepository $mediaRepository): Response
    {
        // Récupérer la catégorie par ID
        $category = $categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        // Récupérer les médias associés à la catégorie
        $medias = $mediaRepository->findByCategory($category);

        //find all categories

        $categories = $categoryRepository->findAll();

        // Passer la catégorie et la liste des médias à la vue
        return $this->render('other/category.html.twig', [
            'category' => $category,
            'medias' => $medias,
            'categories' => $categories
        ]);
    }
    #[Route(path: '/discover', name: 'page_discover')]
   public function discover(CategoryRepository $categoryRepository,MediaRepository $mediaRepository):Response
{
    $medias = $mediaRepository->findPopular(self::POPULAR_MEDIA_LIMIT);
       // recuperer toutes les catégories : un tableau d'objets
        $categories = $categoryRepository->findAll();
       return $this->render('other/discover.html.twig', ['categories' => $categories, 'medias' => $medias]);
   }
}
