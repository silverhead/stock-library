<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
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
}
