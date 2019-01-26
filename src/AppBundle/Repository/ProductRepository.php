<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Storage;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{

    public function searchByLabel($search)
    {
        return $this->createQueryBuilder('p')
            ->where('p.label LIKE :label')
            ->where('p.reference LIKE :label')
            ->where('p.keywords LIKE :label')
            ->where('p.description LIKE :label')
            ->setParameter('label', '%'.$search.'%')
            ->getQuery()->getResult();
    }


    public function findAllByUser(User $user)
    {
        return $this->createQueryBuilder('p')
            ->select('p, pu')
            ->Join('p.productByUser', 'pu')
            ->where('pu.user = :user')
            ->setParameter('user', $user)
            ->getQuery()->getResult();
    }

    public function findProductsByStorageAndUser(Storage $storage, User $user)
    {
        return $this->createQueryBuilder("p")
            ->select("p, pu")
            ->Join('p.productByUser', 'pu')
            ->where("pu.user = :user")
            ->setParameter("user", $user)
            ->andWhere("pu.storage = :storage")
            ->setParameter("storage", $storage)
            ->getQuery()->getResult();
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
