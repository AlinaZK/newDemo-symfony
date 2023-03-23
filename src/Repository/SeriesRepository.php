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
 * @method Serie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serie[]    findAll()
 * @method Serie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeriesRepository extends ServiceEntityRepository
{
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
          /*   //Roquet SQL ce fais dans SeriesRepository
    public function findBestSeries(){
        //recherche des meilleur series en DQL
        $entityManager = $this->getEntityManager();

        $dql = "SELECT s 
                FROM App\Entity\Serie s 
                WHERE s.popularity > 100
                AND s.vote > 8
                ORDER BY s.popularity DESC";

        $query = $entityManager->createQuery($dql);

        $query->setMaxResults(30);

        return $query->getResult();
    */
    public function findBestSeries (){
        // avec QuerrBuilder
        $queryBuilder = $this ->createQueryBuilder('s'); // attend un alisas
        $queryBuilder->leftJoin('s.seasons', 'seas' ); // left join pour recupere les info de season a traver de serie qui n'ont pas de saisons
        $queryBuilder->addSelect('seas'); // recoupere maxresult
        $queryBuilder->andWhere('s.popularity > 100'); //filtre popularite jus100
        $queryBuilder->andWhere('s.vote > 8');
        $queryBuilder->addOrderBy('s.popularity', 'DESC'); // trie decroisent
        $queryBuilder->addOrderBy('s.vote','DESC'); // trie decroisent

        $query = $queryBuilder->getQuery(); // recoupere info tape just avent
        $query->setMaxResults(30); // set maximalno do 30

        $paginator = new Paginator($query);// recoupere addSelector pour change la cantite de resulta avec nouvel variable
        return $paginator;
        //return $query->getResult(); // recoupere resulta
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
