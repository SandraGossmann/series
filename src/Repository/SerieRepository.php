<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 *
 * @method Serie|null find($id, $lockMode = null, $lockVersion = null)
 * //criteria tableau de clauses where
 * @method Serie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serie[]    findAll()
 * //limit nb de lignes à récupérer
 * //offset no auquel on commence
 * @method Serie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerieRepository extends ServiceEntityRepository
{
    const SERIE_LIMITE = 48;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    public function save(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function findBestSeries(int $page){

        $offset = ($page - 1) * self::SERIE_LIMITE;
        //En QueryBuilder
        $qb = $this->createQueryBuilder('s');
        $qb
            ->leftJoin('s.seasons', 'sea')
            ->addSelect('sea')
            ->addOrderBy('s.popularity', 'DESC')
//            ->andWhere('s.vote > 8')
//            ->andWhere('s.popularity > 100')
            ->setFirstResult($offset)
            ->setMaxResults(self::SERIE_LIMITE);
        $query = $qb->getQuery();
        $paginator = new Paginator($query);
        return $paginator;
    }
}
//En DQL find bestSeries
//Récupération séries avec un vote > 8 et popularité > 100
//ordonné par popularité

//        $dql = "SELECT s FROM App\Entity\Serie s
//                WHERE s.vote > 8 AND s.popularity > 100
//                ORDER BY s.popularity DESC";
//        $query = $this->getEntityManager()->createQuery($dql);
//        //ajoute une limite de résultats
//        $query->setMaxResults(56);
//        return $query->getResult();