<?php

namespace App\Repository;

use App\Entity\Sitemap;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sitemap|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sitemap|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sitemap[]    findAll()
 * @method Sitemap[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SitemapRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sitemap::class);
    }

    // /**
    //  * @return Sitemap[] Returns an array of Sitemap objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sitemap
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function truncateTable($prefix)
    {

        // return $this->getEntityManager()
        // ->createQuery(
        //     'TRUNCATE TABLE  mtdmfr_sitemap'
        // )
        // ->getResult();



        // return $this->createQueryBuilder('s')
        // ->update()
        // ->set('s.id',1)
        // ->getQuery()
        // ->getResult();


        // return $this->getEntityManager()
        // ->createQuery(
        //     'update App\Entity\Sitemap  s set s.id= :val '
        // )->setParameter('val', 1)
        // ->getResult();

        // return $this->getEntityManager()
        // ->createQuery(
        //     'ALTER TABLE App\Entity\Sitemap s AUTO_INCREMENT = 1 '
        // )
        // ->getResult();

        $connection = $this->getEntityManager()->getConnection();
        $connection->prepare('ALTER TABLE ' . $prefix . 'sitemap AUTO_INCREMENT = 1;')->execute();
        return ($connection);

    }
    /**
     * get all  departments
     */
    public function urlsDepartements($key)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.path LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $key . '%')
            ->getQuery()->getResult();
    }

    /**
     * suprimer tous les sitemap 
     */
    public function deletAll()
    {
        return $this->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * get pages with department
     */
    public function pageDepartements($posts, $departements)
    {
        $table = [10, 11, 12, 13, 14, 15, 16, 17, 18, 19];
        foreach ($posts as $p) {
            if (preg_match('~[0-9]+~', $p->getPostName())) {
                foreach ($departements as $dep) {
                    //if(in_array($dep->getCode(),$table)==false){
                    if ((str_contains($p->getPostName(), 'paris-'))) {
                        if ($dep->getCode() == 75) {
                            $result[$dep->getCode()][] = $p;
                        }
                    } elseif (in_array($dep->getCode(), $table)) {
                        if ((str_contains($p->getPostName(), '-' . $dep->getCode() . ''))) {
                            $result[$dep->getCode()][] = $p;
                        }
                    } else {
                        if ((str_contains($p->getPostName(), '-' . $dep->getCode() . ''))) {
                            $result[$dep->getCode()][] = $p;
                        }
                    }
                    // }


                }
            } else {
                $result['all'][] = $p;
            }

        }
        // dd($result);
        return $result;
    }

}
