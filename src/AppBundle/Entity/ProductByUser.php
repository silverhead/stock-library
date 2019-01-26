<?php

namespace AppBundle\Entity;

use AppBundle\Form\Model\ProductModel;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ProductUser
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductByUserRepository")
 */
class ProductByUser
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="product")
     */
    private $user;

    /**
     * @var Product
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="productByUser")
     */
    private $product;

    /**
     * @var Storage
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Storage", inversedBy="products")
     */
    private $storage;
    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=false)
     */
    private $quantityLimitAlert;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return ProductByUser
     */
    public function setUser(User $user): ProductByUser
    {
        $this->user = $user;

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
     * @return ProductByUser
     */
    public function setProduct(Product $product): ProductByUser
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return Product
     */
    public function setQuantity(int $quantity): Product
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantityLimitAlert(): ?int
    {
        return $this->quantityLimitAlert;
    }

    /**
     * @param int $quantityLimitAlert
     * @return Product
     */
    public function setQuantityLimitAlert(int $quantityLimitAlert): Product
    {
        $this->quantityLimitAlert = $quantityLimitAlert;

        return $this;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set storages.
     *
     * @param Storage|null $storage
     *
     * @return ProductByUser
     */
    public function setStorage(Storage $storage = null)
    {
        $this->storage = $storage;

        return $this;
    }

    /**
     * Get storages.
     *
     * @return \AppBundle\Entity\Storage|null
     */
    public function getStorage()
    {
        return $this->storage;
    }

    public function setByProductModel(ProductModel $productModel)
    {
        $this->storage = $productModel->getStorage();
        $this->quantity = $productModel->getQuantity();
        $this->quantityLimitAlert = $productModel->getQuantityLimitAlert();
    }
}
