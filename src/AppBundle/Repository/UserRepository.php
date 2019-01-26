<?php
namespace AppBundle\Repository;

use AppBundle\Repository\PaginatorRepositoryInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 * @package AppBundle\Repository
 */
class UserRepository extends EntityRepository
{
    public function findByUsernameOrEmail($username)
    {
        return $this->createQueryBuilder("u")
            ->where("u.username = :username")
            ->orWhere("u.email = :username")
            ->setParameter("username", $username)
            ->getQuery()->getOneOrNullResult()
            ;
    }
}