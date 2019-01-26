<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Product;
use AppBundle\Entity\Storage;
use AppBundle\Entity\User;
use \Doctrine\ORM\EntityRepository;

class ProductByUserRepository extends EntityRepository
{
    public function findOnceByProductAndUser(Product $product, User $user)
    {
        return $this->createQueryBuilder("productByUser")
            ->where("productByUser.user = :user")
            ->setParameter("user", $user)
            ->andWhere("productByUser.product = :product")
            ->setParameter("product", $product)
            ->getQuery()->getOneOrNullResult()
        ;
    }

    public function findAllByProductAndAnotherUser(Product $product, User $user)
    {
        return $this->createQueryBuilder("productByUser")
            ->where("productByUser.user != :user")
            ->setParameter("user", $user)
            ->andWhere("productByUser.product = :product")
            ->setParameter("product", $product)
            ->getQuery()->getResult()
            ;
    }
}