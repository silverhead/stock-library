<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Storage;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ProductRepository extends EntityRepository
{

    public function searchByLabel($search)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.label LIKE :label')
            ->setParameter("label", $search)
            ->orWhere('p.reference LIKE :reference')
            ->setParameter("reference", $search)
        ;
        $searchs = explode(" ", $search);

        foreach ($searchs as $value){
            $qb->orWhere("p.label LIKE :value")
                ->orWhere("p.reference LIKE :value")
                ->orWhere("p.keywords LIKE :value")
                ->orWhere("p.description LIKE :value")
                ->setParameter('value', '%'.$value.'%')
            ;
        }
       return $qb->getQuery()->getResult();
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
        $ids = $storage->getChildren()->map(function($child){
            return $child->getId();
        });

        $ids->add($storage->getId());

        return $this->createQueryBuilder("p")
            ->select("p, pu")
            ->Join('p.productByUser', 'pu')
            ->where("pu.user = :user")
            ->setParameter("user", $user)
            ->andWhere("pu.storage in (:ids)")
            ->setParameter("ids", implode(",", $ids->toArray()))
            ->getQuery()->getResult();
    }

    public function findPaginatorProducts($orders, int $start, int $offset): Paginator
    {
        $qb = $this->createQueryBuilder('p');

        foreach ($orders as $sort => $order){
            $qb->addOrderBy($sort, $order);
        }

        $qb->setFirstResult($start)
            ->setMaxResults($offset);

        return new Paginator($qb->getQuery());
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
