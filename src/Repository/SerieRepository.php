<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }


    public function findBestSeries()
    {
        //récupération de l'entitymanager
//        $em = $this->getEntityManager();

        //en DQL
//        $dql = "SELECT s FROM App\Entity\Serie AS s
//                WHERE s.popularity > 200
//                ORDER BY s.vote DESC";
//        //création de la query
//        $query = $em->createQuery($dql);

        //en QueryBuilder
        $qb = $this->createQueryBuilder('s');

        $qb->andWhere("s.popularity > 200")
            ->addOrderBy("s.vote", "DESC");
        $query = $qb->getQuery();

        //pareil pour les 2 possibilités
        //set de la limite
        $query->setMaxResults(10);
        //retourne les résultats de la requête
        return $query->getResult();
    }





    //    /**
    //     * @return Serie[] Returns an array of Serie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Serie
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
