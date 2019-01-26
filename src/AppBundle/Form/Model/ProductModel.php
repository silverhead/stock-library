<?php

namespace AppBundle\Form\Model;


use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\Storage;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;

class ProductModel
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $reference;

    /**
     * @var string
     */
    private $keywords;

    /**
     * @var string
     */
    private $description;

    /**
     * @var File
     */
    private $pictureFile;

    /**
     * @var ArrayCollection
     */
    private $categories;

    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var integer
     */
    private $quantity;

    /**
     * @var integer
     */
    private $quantityLimitAlert;

    /**
     * @var string
     */
    private $picture;

    function __construct(Product $product, $user)
    {
        $this->id = $product->getId();
        $this->label = $product->getLabel();
        $this->reference = $product->getReference();
        $this->description = $product->getDescription();
        $this->keywords = $product->getKeywords();
        $this->picture = $product->getPicture();
        $this->categories = $product->getCategories();

        $productByUser = $product->getProductByUserFiltered($user);

        $this->quantity = 0;
        $this->quantityLimitAlert = 0;

        if (null !== $productByUser && $productByUser->count() > 0){
            $this->storage = $productByUser[0]->getStorage();
            $this->quantity = $productByUser[0]->getQuantity();
            $this->quantityLimitAlert = $productByUser[0]->getQuantityLimitAlert();
        }
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return ProductModel
     */
    public function setId(int $id): ProductModel
    {
        $this->id = $id;

        return $this;
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
     * @return ProductModel
     */
    public function setLabel(string $label): ProductModel
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
     * @return ProductModel
     */
    public function setReference(string $reference): ProductModel
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return string
     */
    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    /**
     * @param string $keywords
     * @return ProductModel
     */
    public function setKeywords(string $keywords): ProductModel
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return ProductModel
     */
    public function setDescription(string $description): ProductModel
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return File
     */
    public function getPictureFile(): ?File
    {
        return $this->pictureFile;
    }

    /**
     * @param File $pictureFile
     * @return ProductModel
     */
    public function setPictureFile(File $pictureFile): ProductModel
    {
        $this->pictureFile = $pictureFile;

        return $this;
    }

    /**
     * @return string
     */
    public function getPicture(): ?string
    {
        return $this->picture;
    }

    /**
     * @param string $pciture
     * @return ProductModel
     */
    public function setPicture(string $picture): ProductModel
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param Category $cateogry
     * @return ProductModel
     */
    public function setCategories($categories): ProductModel
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return Storage
     */
    public function getStorage(): ?Storage
    {
        return $this->storage;
    }

    /**
     * @param Storage $storage
     * @return ProductModel
     */
    public function setStorage(Storage $storage): ProductModel
    {
        $this->storage = $storage;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return ProductModel
     */
    public function setQuantity(int $quantity): ProductModel
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantityLimitAlert(): int
    {
        return $this->quantityLimitAlert;
    }

    /**
     * @param int $quantityLimitAlert
     * @return ProductModel
     */
    public function setQuantityLimitAlert(int $quantityLimitAlert): ProductModel
    {
        $this->quantityLimitAlert = $quantityLimitAlert;

        return $this;
    }
}