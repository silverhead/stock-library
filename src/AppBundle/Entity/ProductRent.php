<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ProductRent
 * @package AppBundle\Entity
 * @ORM\Entity()
 */
class ProductRent
{
    /**
     * @var ProductByUser
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\ProductByUser")
     */
    private $productByUser;

    /**
     * @var User
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User")
     */
    private $renter;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $dateStart;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $dateEnd;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return ProductByUser
     */
    public function getProductByUser(): ProductByUser
    {
        return $this->productByUser;
    }

    /**
     * @param ProductByUser $productByUser
     * @return ProductRent
     */
    public function setProductByUser(ProductByUser $productByUser): ProductRent
    {
        $this->productByUser = $productByUser;

        return $this;
    }

    /**
     * @return User
     */
    public function getRenter(): User
    {
        return $this->renter;
    }

    /**
     * @param User $renter
     * @return ProductRent
     */
    public function setRenter(User $renter): ProductRent
    {
        $this->renter = $renter;

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
     * @return ProductRent
     */
    public function setQuantity(int $quantity): ProductRent
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateStart(): \DateTime
    {
        return $this->dateStart;
    }

    /**
     * @param \DateTime $dateStart
     * @return ProductRent
     */
    public function setDateStart(\DateTime $dateStart): ProductRent
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateEnd(): \DateTime
    {
        return $this->dateEnd;
    }

    /**
     * @param \DateTime $dateEnd
     * @return ProductRent
     */
    public function setDateEnd(\DateTime $dateEnd): ProductRent
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }
}