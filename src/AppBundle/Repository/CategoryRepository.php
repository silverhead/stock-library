<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CategoryRepository extends NestedTreeRepository
{
    public function reorderHierarchy(Category $category)
    {
        if ($category->getRoot() !== null){
            $parent = $this->findOneById($category->getRoot());
            $this->reorder($parent, 'label', 'ASC');
        }
    }

    public function findByPaginate(array $criteria, array $orders, int $start, int $offset)
    {
        $qb = $this->createQueryBuilder('c');

        $i = 0;
        foreach ($criteria as $property => $criterion)
        {
            $qb->andWhere("c.".$property . " = :value" . $i)
                ->setParameter(":value".$i, $criterion);
            $i++;
        }

        foreach ($orders as $sort => $order){
            $qb->addOrderBy("c.".$sort, $order);
        }

        $qb->setFirstResult($start)
            ->setMaxResults($offset);

        return new Paginator($qb->getQuery());
    }
}
