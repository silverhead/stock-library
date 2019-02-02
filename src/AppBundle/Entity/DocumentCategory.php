<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * DocumentProduct
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DocumentCategoryRepository")
 */
class DocumentCategory extends AbstractDocument
{
    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="documents")
     */
    private $category;

    public function setOwner(DocumentOwnerInterface $owner): AbstractDocument
    {
        $this->category = $owner;
        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Product $category
     * @return DocumentCategory
     */
    public function setCategory(Category $category): DocumentCategory
    {
        $this->category = $category;

        return $this;
    }


}
