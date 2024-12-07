<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Playlist>
 */
class PlaylistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    //    /**
    //     * @return Playlist[] Returns an array of Playlist objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Playlist
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    /**
     * Retourne les playlists avec le nombre de films et de séries associés.
     */
//    public function findPlaylistsWithMediaCounts(): array
//    {
//        return $this->createQueryBuilder('p')
//            ->select('p as playlist,
//                  SUM(CASE WHEN m.discr = :movie THEN 1 ELSE 0 END) AS moviesCount,
//                  SUM(CASE WHEN m.discr = :serie THEN 1 ELSE 0 END) AS seriesCount')
//            ->leftJoin('p.playlistMedia', 'pm')
//            ->leftJoin('pm.media', 'm')
//            ->setParameter('movie', 'movie')
//            ->setParameter('serie', 'serie')
//            ->groupBy('p.id')
//            ->getQuery()
//            ->getResult();
//    }

}
