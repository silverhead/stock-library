<?php

namespace AppBundle\Repository;

trait FilterQueryBuilderHelperTrait
{
    public function filter($qb, $criteria)
    {
        foreach ($criteria as $propriety => $criterion){

            $exp = null;

            switch ($criterion->operator){
                case 'in':
                    $exp = $qb->expr()->in($propriety, implode(",", $criterion->search));
                    break;
                case 'like%':
                    $exp = $qb->expr()->like($propriety, $qb->expr()->literal($criterion->search . '%') );
                    break;
                case '%like%':
                    $exp = $qb->expr()->like($propriety, $qb->expr()->literal('%'. $criterion->search . '%') );
                    break;
                case 'equal':
                    $exp = $qb->expr()->eq($propriety, $criterion->search);
                    break;
            }

            if (null !== $exp){
                $qb->andWhere($exp);
            }
        }
    }
}