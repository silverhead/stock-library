<?php

namespace AppBundle\Form\Model;

class ProductListSorterModel
{
    /**
     * @var string
     */
    private $productLabelOrder;

    /**
     * @var string
     */
    private $categoryLabelOrder;

    /**
     * @var string
     */
    private $storageLabelOrder;

    /**
     * @var string
     */
    private $quantityOrder;

    /**
     * @return string
     */
    public function getProductLabelOrder(): ?string
    {
        return $this->productLabelOrder;
    }

    /**
     * @param string $productLabelOrder
     * @return ProductListSorterModel
     */
    public function setProductLabelOrder(string $productLabelOrder): ProductListSorterModel
    {
        $this->productLabelOrder = $productLabelOrder;

        return $this;
    }

    /**
     * @return string
     */
    public function getCategoryLabelOrder(): ?string
    {
        return $this->categoryLabelOrder;
    }

    /**
     * @param string $categoryLabelOrder
     * @return ProductListSorterModel
     */
    public function setCategoryLabelOrder(string $categoryLabelOrder): ProductListSorterModel
    {
        $this->categoryLabelOrder = $categoryLabelOrder;

        return $this;
    }

    /**
     * @return string
     */
    public function getStorageLabelOrder(): ?string
    {
        return $this->storageLabelOrder;
    }

    /**
     * @param string $storageLabelOrder
     * @return ProductListSorterModel
     */
    public function setStorageLabelOrder(string $storageLabelOrder): ProductListSorterModel
    {
        $this->storageLabelOrder = $storageLabelOrder;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuantityOrder(): ?string
    {
        return $this->quantityOrder;
    }

    /**
     * @param string $quantityOrder
     * @return ProductListSorterModel
     */
    public function setQuantityOrder(string $quantityOrder): ProductListSorterModel
    {
        $this->quantityOrder = $quantityOrder;

        return $this;
    }


}