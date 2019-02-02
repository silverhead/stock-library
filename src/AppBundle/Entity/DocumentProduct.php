<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * DocumentProduct
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DocumentProductRepository")
 */
class DocumentProduct extends AbstractDocument
{
    /**
     * @var Product
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="documents")
     */
    private $product;

    public function setOwner(DocumentOwnerInterface $owner): AbstractDocument
    {
        $this->product = $owner;
        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return DocumentProduct
     */
    public function setProduct(Product $product): DocumentProduct
    {
        $this->product = $product;

        return $this;
    }


}
