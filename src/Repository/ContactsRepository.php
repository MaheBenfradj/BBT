<?php

namespace App\Repository;

use App\Entity\Contacts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contacts>
 *
 * @method Contacts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contacts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contacts[]    findAll()
 * @method Contacts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contacts::class);
    }

    public function add(Contacts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Contacts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function totalContacts()
    {
        $today = new \DateTime('now');
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('SUBSTRING(c.dateCreation,1,4) = SUBSTRING(:now,1,4)')
            ->setParameter('now', $today)
            //->orderBy('m.id', 'ASC')
            //  ->setMaxResults(10)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function totalContactsTODAY()
    {
        $today = new \DateTime('now');
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.dateCreation = :now')
            ->setParameter('now', $today)
            //->orderBy('m.id', 'ASC')
            //  ->setMaxResults(10)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function totalContactsYESTERDAY()
    {
        $today = new \DateTime('YESTERDAY');
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.dateCreation = :now')
            ->setParameter('now', $today)
            //->orderBy('m.id', 'ASC')
            //  ->setMaxResults(10)
            ->getQuery()
            ->getOneOrNullResult();
    }


//    /**
//     * @return Contacts[] Returns an array of Contacts objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Contacts
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
