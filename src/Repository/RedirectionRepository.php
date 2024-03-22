<?php

namespace App\Repository;

use App\Entity\Redirection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Redirection|null find($id, $lockMode = null, $lockVersion = null)
 * @method Redirection|null findOneBy(array $criteria, array $orderBy = null)
 * @method Redirection[]    findAll()
 * @method Redirection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RedirectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Redirection::class);
    }

    public function findOneByOldExceptOne($old, $id):?Redirection
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id != :id')
            ->andWhere('r.old_root = :old')
            ->setParameters(new ArrayCollection([
                new Parameter('old', $old),
                new Parameter('id', $id),
            ]))
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
  
    /**
     * @return Redirection[] Returns an array of Redirection objects
    */
    public function findByNewRootExceptOne($new, $id)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id != :id')
            ->andWhere('r.new_root = :new')
            ->setParameters(new ArrayCollection([
                new Parameter('new', $new),
                new Parameter('id', $id),
            ]))
            ->getQuery()
            ->getResult()
        ;
    }
}
