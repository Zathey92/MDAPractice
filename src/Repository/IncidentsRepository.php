<?php

namespace App\Repository;

use App\Entity\Incidents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Incidents|null find($id, $lockMode = null, $lockVersion = null)
 * @method Incidents|null findOneBy(array $criteria, array $orderBy = null)
 * @method Incidents[]    findAll()
 * @method Incidents[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncidentsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Incidents::class);
    }

    public function addIncident($incident)
    {
        $em = $this->getEntityManager();
        try {
            $em->persist($incident);
            $em->flush();
        } catch (OptimisticLockException $e) {
            return $e;

        } catch( \PDOException $e ) {
            return $e;

        } catch (ORMException $e) {
            return $e;
        }
        return null;
    }

//    /**
//     * @return Doctors[] Returns an array of Doctors objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Doctors
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}