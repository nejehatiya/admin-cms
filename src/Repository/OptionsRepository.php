<?php

namespace App\Repository;

use App\Entity\Options;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Options|null find($id, $lockMode = null, $lockVersion = null)
 * @method Options|null findOneBy(array $criteria, array $orderBy = null)
 * @method Options[]    findAll()
 * @method Options[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OptionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Options::class);
    }

    // /**
    //  * @return Options[] Returns an array of Options objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /**
     * @return Options[] Returns an array of Options objects
     */
    public function getListByName($search="")
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.option_name like :search')
            ->setParameter('search', '%'.$search.'%')
            ->getQuery()
            ->getResult()
        ;
    }

    
    // get config value name
    public function findByKey($key){
        return $this->createQueryBuilder('c')
            ->andWhere('c.option_name = :val')
            ->setParameter('val', $key)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function deleteAllRecords()
    {
        $query = $this->createQueryBuilder('d')
            ->delete()
            ->getQuery()
            ->execute();
        return $query;
    }
    public function resetAutoIncrement()
    {
        $connection = $this->getEntityManager()->getConnection();
        $platform   = $connection->getDatabasePlatform();
        
        $truncateSql = $platform->getTruncateTableSQL('configuration', true /* whether to cascade */);
        $connection->executeUpdate($truncateSql);
    }
}
