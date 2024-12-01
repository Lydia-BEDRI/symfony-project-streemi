<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Media;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Media>
 */
class MediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Media::class);
    }

//    /**
//     * @return Media[] Returns an array of Media objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Media
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findPopular(int $maxResults = 10): array
    {
        //media OneToMany WatchHistories
        return $this->createQueryBuilder('m')
            ->leftJoin('m.watchHistories', 'wh')
            ->groupBy('m.id')
            ->orderBy('COUNT(wh)', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
    }
    // Méthode pour récupérer les médias associés à une catégorie
    public function findByCategory(Category $category)
    {
        return $this->createQueryBuilder('m')
            ->innerJoin('m.categories', 'c') // Associer la table media_category
            ->where('c = :category') // Filtrer par la catégorie
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult();
    }

}
