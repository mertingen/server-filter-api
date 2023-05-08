<?php

namespace App\Repository;

use App\Entity\Server;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Server>
 *
 * @method Server|null find($id, $lockMode = null, $lockVersion = null)
 * @method Server|null findOneBy(array $criteria, array $orderBy = null)
 * @method Server[]    findAll()
 * @method Server[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Server::class);
    }

    public function save(Server $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Server $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param array $filterParams
     * @return float|int|mixed|string
     */
    public function findByFilter(array $filterParams): mixed
    {
        $qb = $this->createQueryBuilder('s');
        if (isset($filterParams['location'])) {
            $qb->andWhere('s.location = :location')
                ->setParameter('location', $filterParams['location']);
        }
        if (isset($filterParams['hddType'])) {
            $qb->andWhere('s.hddType = :hddType')
                ->setParameter('hddType', $filterParams['hddType']);
        }
        if (isset($filterParams['ramValues'])) {
            $qb->andWhere("s.actualRamSize IN(:ramValues)")
                ->setParameter('ramValues', array_values($filterParams['ramValues']));
        }
        //if (isset($filterParams['minStorage']) && isset($filterParams['maxStorage'])) {
        //    $qb->andWhere($qb->expr()->gte('s.actualHddSize', $filterParams['minStorage']))
        //        ->andWhere($qb->expr()->lte('s.actualHddSize', $filterParams['maxStorage']));
        //}
        if (isset($filterParams['storage'])) {
            $qb->andWhere('s.actualHddSize = :storage')
                ->setParameter('storage', $filterParams['storage']);
        }

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return server[] Returns an array of server objects
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

    //    public function findOneBySomeField($value): ?server
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
