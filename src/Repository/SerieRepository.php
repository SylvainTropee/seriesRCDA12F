<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
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


    public function findBestSeries(int $page)
    {
        //récupération de l'entitymanager
//        $em = $this->getEntityManager();

        //en DQL
//        $dql = "SELECT s FROM App\Entity\Serie AS s
//                WHERE s.popularity > 200
//                ORDER BY s.vote DESC";
//        //création de la query
//        $query = $em->createQuery($dql);

        //page = 1 ; 0 -> 19
        //page =  2 ; 20 ->39
        $limit = Serie::SERIE_PER_PAGE;
        $offset = ($page - 1) * $limit;

        //en QueryBuilder
        $qb = $this->createQueryBuilder('s');
        $qb->leftJoin('s.seasons', 'seas');
        $qb->addSelect('seas');

        $qb->addOrderBy("s.popularity", "DESC");
        $query = $qb->getQuery();

        //pareil pour les 2 possibilités
        //set de la limite
        $query->setMaxResults($limit);
        $query->setFirstResult($offset);

        //ajout du paginator pour gérer les différences dû à la jointure
        $paginator = new Paginator($query);
        //retourne les résultats de la requête
        return $paginator;
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
