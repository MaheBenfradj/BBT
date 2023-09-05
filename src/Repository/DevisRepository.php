<?php

namespace App\Repository;

use App\Entity\Devis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Devis>
 *
 * @method Devis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Devis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Devis[]    findAll()
 * @method Devis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Devis::class);
    }

    public function add(Devis $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Devis $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function totalDevis()
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

    public function DevisEtat()
    {
        return $this->createQueryBuilder('c')
            ->select('count(c) as nombreD,c.etat')
            ->groupBy('c.etat')
            ->getQuery()
            ->getResult();
    }

    public function DevisAFacturer()
    {
        return $this->createQueryBuilder('c')
            ->where('c.etat LIKE :et')
            ->setParameter('et', "A facturer")
            ->getQuery()
            ->getResult();
    }

    public function DevisFacturé()
    {
        return $this->createQueryBuilder('c')
            ->where('c.etat LIKE :et')
            ->setParameter('et', "Facturé")
            ->getQuery()
            ->getResult();
    }

    public function DevisTraitées()
    {
        return $this->createQueryBuilder('c')
            ->where('c.etat LIKE :et')
            ->setParameter('et', "Traitée")
            ->getQuery()
            ->getResult();
    }

    public function DevisRefusé()
    {
        return $this->createQueryBuilder('c')
            ->where('c.etat LIKE :et')
            ->setParameter('et', "Refusé")
            ->getQuery()
            ->getResult();
    }
    public function DevisEnAttente()
    {
        return $this->createQueryBuilder('c')
            ->where('c.etat LIKE :et')
            ->setParameter('et', "En attente")
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Devis[] Returns an array of Devis objects
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

//    public function findOneBySomeField($value): ?Devis
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
