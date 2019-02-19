<?php

namespace AppBundle\Form\Model;

use Doctrine\Common\Collections\ArrayCollection;

class ProductListFilterModel
{
    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $reference;

    /**
     * @var ArrayCollection
     */
    private $categories;

    /**
     * @var ArrayCollection
     */
    private $storage;

    /**
     * @var bool
     */
    private $haveProductOnly;

    public function __construct()
    {
        $this->haveProductOnly = true;
    }

    /**
     * @return string
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return ProductListFilterModel
     */
    public function setLabel(string $label = null): ProductListFilterModel
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     * @return ProductListFilterModel
     */
    public function setReference(string $reference = null): ProductListFilterModel
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCategories(): ?ArrayCollection
    {
        return $this->categories;
    }

    /**
     * @param ArrayCollection $categories
     * @return ProductListFilterModel
     */
    public function setCategories(ArrayCollection $categories): ProductListFilterModel
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getStorage(): ?ArrayCollection
    {
        return $this->storage;
    }

    /**
     * @param ArrayCollection $storage
     * @return ProductListFilterModel
     */
    public function setStorage(ArrayCollection $storage): ProductListFilterModel
    {
        $this->storage = $storage;

        return $this;
    }

    /**
     * @return bool
     */
    public function haveProductOnly(): ?bool
    {
        return $this->haveProductOnly;
    }

    /**
     * @param bool $haveProduct
     * @return ProductListFilterModel
     */
    public function setHaveProductOnly(bool $haveProduct): ProductListFilterModel
    {
        $this->haveProductOnly = $haveProduct;

        return $this;
    }
}