<?php

namespace App\Repository;

use App\Entity\DemandeRDV;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DemandeRDV>
 *
 * @method DemandeRDV|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeRDV|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeRDV[]    findAll()
 * @method DemandeRDV[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeRDVRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeRDV::class);
    }

    public function add(DemandeRDV $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DemandeRDV $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function totalDemandeRDV()
    {
        $today = new \DateTime('now');
        return $this->createQueryBuilder('d')
            ->select('count(d.id)')
            ->where('SUBSTRING(d.dateCreation,1,4) = SUBSTRING(:now,1,4)')
            ->setParameter('now', $today)
            //->orderBy('m.id', 'ASC')
            //  ->setMaxResults(10)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return DemandeRDV[] Returns an array of DemandeRDV objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DemandeRDV
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
