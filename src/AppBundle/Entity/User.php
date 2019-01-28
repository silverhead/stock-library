<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
/**
 * Class User
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var array
     * @ORM\Column(type="array", nullable=false)
     */
    protected $roles;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $password;

    /**
     * @var string
     */
    protected $plainPassword;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $salt;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false, unique=false)
     */
    protected $firstName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false, unique=false)
     */
    protected $lastName;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastDateAskNewPassword;

    /**
     * @var string
     * @ORM\Column(name="ask_password_token", type="string", length=255, nullable=true)
     */
    protected $askPasswordToken;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $active;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $createAt;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @var ProductByUser
     * @ORM\OneToMany(targetEntity="ProductByUser", mappedBy="product")
     */
    protected $product;

    /**
     * @var Storage
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Storage", mappedBy="user")
     */
    protected $storages;

    /**
     * @param mixed $roles
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param mixed $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param mixed $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @param mixed $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFullName()
    {
        return $this->getFirstName().' '.$this->getLastName();
    }

    /**
     * @return \DateTime
     */
    public function getLastDateAskNewPassword()
    {
        return $this->lastDateAskNewPassword;
    }

    /**
     * @param \DateTime $lastDateAskNewPassword
     * @return User
     */
    public function setLastDateAskNewPassword(\DateTime $lastDateAskNewPassword): User
    {
        $this->lastDateAskNewPassword = $lastDateAskNewPassword;

        return $this;
    }

    /**
     * @return string
     */
    public function getAskPasswordToken()
    {
        return $this->askPasswordToken;
    }

    /**
     * @param string $askPasswordToken
     * @return User
     */
    public function setAskPasswordToken(string $askPasswordToken): User
    {
        $this->askPasswordToken = $askPasswordToken;

        return $this;
    }

    public function isDeletable()
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    public function isActive()
    {
        return $this->active;
    }

    /**
     * @return string
     */
    public function getPlainPassword():? string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     * @return User
     */
    public function setPlainPassword(string $plainPassword): User
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * @param \DateTime $createAt
     * @return User
     */
    public function setCreateAt($createAt) : User
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return Member
     */
    public function setUpdatedAt(\DateTime $updatedAt): User
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return ProductByUser
     */
    public function getProduct(): ProductByUser
    {
        return $this->product;
    }

    /**
     * @param ProductByUser $product
     * @return User
     */
    public function setProduct(ProductByUser $product): User
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->storages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add product.
     *
     * @param \AppBundle\Entity\ProductByUser $product
     *
     * @return User
     */
    public function addProduct(\AppBundle\Entity\ProductByUser $product)
    {
        $this->product[] = $product;

        return $this;
    }

    /**
     * Remove product.
     *
     * @param \AppBundle\Entity\ProductByUser $product
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProduct(\AppBundle\Entity\ProductByUser $product)
    {
        return $this->product->removeElement($product);
    }

    /**
     * Add storage.
     *
     * @param \AppBundle\Entity\Storage $storage
     *
     * @return User
     */
    public function addStorage(\AppBundle\Entity\Storage $storage)
    {
        $this->storages[] = $storage;

        return $this;
    }

    /**
     * Remove storage.
     *
     * @param \AppBundle\Entity\Storage $storage
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeStorage(\AppBundle\Entity\Storage $storage)
    {
        return $this->storages->removeElement($storage);
    }

    /**
     * Get storages.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStorages()
    {
        return $this->storages;
    }
}
