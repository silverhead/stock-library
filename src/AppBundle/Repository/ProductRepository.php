<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use AppBundle\Entity\Storage;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ProductRepository extends EntityRepository
{
    use FilterQueryBuilderHelperTrait;

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

    public function findProductsByStorageAndUser(Storage $storage, User $user, $orders = array('p.label' => 'ASC'))
    {
        $ids = $storage->getChildren()->map(function($child){
            return $child->getId();
        });

        $ids->add($storage->getId());

        $qb = $this->createQueryBuilder("p")
            ->select("p, pu")
            ->Join('p.productByUser', 'pu')
            ->where("pu.user = :user")
            ->setParameter("user", $user)
            ->andWhere("pu.storage in (:ids)")
            ->setParameter("ids", implode(",", $ids->toArray()))
            ;

        foreach ($orders as $sort => $order){
            $qb->addOrderBy($sort, $order);
        }

        return $qb->getQuery()->getResult();
    }

    public function findProductsByCategory(Category $category, $orders)
    {
        $ids = $category->getChildren()->map(function($child){
            return $child->getId();
        });

        $ids->add($category->getId());

        $qb = $this->createQueryBuilder("p")
            ->select("p")
            ->join("p.categories", "c")
            ->where("c.id in (:ids)")
            ->setParameter("ids", implode(",", $ids->toArray()));

        foreach ($orders as $sort => $order){
            $qb->addOrderBy($sort, $order);
        }

        return $qb->getQuery()->getResult();
    }

    public function findPaginatorProducts($criteria, $orders, int $start, int $offset): Paginator
    {
        $qb = $this->createQueryBuilder('p');
        $qb->select("p, productByUser, category")
            ->leftJoin("p.productByUser", "productByUser")
            ->leftJoin("p.categories", "category")
            ->leftJoin("productByUser.storage", "storage")
        ;

        $this->filter($qb, $criteria);

        foreach ($criteria  as $property => $criterion) {
            switch ($criterion->operator){
                case 'allOfCat':
                    /**
                     * @var ArrayCollection
                     */
                    $storages = $criterion->search;

                    if ($storages->count() == 1){
                        $storage = $storages->first();
                        $qb->andWhere("category.lft >= :lft")->setParameter("lft", $storage->getLft());
                        $qb->andWhere("category.rgt <= :rgt")->setParameter("rgt", $storage->getRgt());
                    }
                    else{
                        $qb->andWhere($qb->expr()->in("category.id",
                            implode(",",
                                $storages->map(function($cat){ return $cat->getId(); })->toArray()
                            )));
                    }

                    break;
                case 'allOfStorage':
                    /**
                     * @var ArrayCollection
                     */
                    $storages = $criterion->search;

                    if ($storages->count() == 1){
                        $storage = $storages->first();
                        $qb->andWhere("storage.lft >= :lft")->setParameter("lft", $storage->getLft());
                        $qb->andWhere("storage.rgt <= :rgt")->setParameter("rgt", $storage->getRgt());
                    }
                    else{
                        $qb->andWhere($qb->expr()->in("storage.id",
                            implode(",",
                                $storages->map(function($storage){ return $storage->getId(); })->toArray()
                            )));
                    }

                    break;
            }
        }

        foreach ($orders as $sort => $order){
            $qb->addOrderBy($sort, $order);
        }

        $qb->setFirstResult($start)
            ->setMaxResults($offset);

        return new Paginator($qb->getQuery());
    }
}
