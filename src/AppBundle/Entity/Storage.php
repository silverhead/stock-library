<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Storage
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="storage")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StorageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Storage
{
    const PUBLIC_PATH = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web';
    const PICTURE_PATH = '/images/stock/storage';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $label;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @var string
     */
    private $pictureOld;

    /**
     * @var File
     */
    private $pictureFile;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="Storage")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Storage", inversedBy="children")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Storage", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $user;

    /**
     * @var Product
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="storage")
     */
    private $products;

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Storage
     */
    public function setPictureFile(File $pictureFile): Storage
    {
        $this->pictureFile = $pictureFile;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreFlush()
     */
    public function preUpload()
    {
        if (null !== $this->getPictureFile()) {
            $this->pictureOld = $this->picture;
            $filename = sha1(uniqid(mt_rand(), true));
            $this->picture = $filename.'.'.$this->getPictureFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getPictureFile()) {
            return;
        }

        if (null !== $this->pictureOld && '' !== $this->pictureOld) {
            if (is_file($this->getUploadRootPictureDir().'/'.$this->pictureOld)){
                // delete the old avatar
                unlink($this->getUploadRootPictureDir().'/'.$this->pictureOld);
                // clear the temp image path
                $this->pictureOld = null;
            }
        }

        $this->getPictureFile()->move($this->getUploadRootPictureDir(), $this->picture);

        $this->pictureFile = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePicturePath()) {
            unlink($file);
        }
    }


    public function getAbsolutePicturePath()
    {
        if (null === $this->picture || '' === $this->picture){
            return null;
        }
        else{
            return $this->getUploadRootPictureDir().'/'.$this->picture;
        }
    }

    public function getPictureWebPath()
    {
        if (null === $this->picture || '' === $this->picture){
            return null;
        }
        else{
            return $this->getUploadPictureDir().DIRECTORY_SEPARATOR.$this->picture;
        }
    }

    protected function getUploadRootPictureDir()
    {
        return self::PUBLIC_PATH.$this->getUploadPictureDir();
    }

    protected function getUploadPictureDir()
    {
        return self::PICTURE_PATH;
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
     * @return Storage
     */
    public function setLabel(string $label): Storage
    {
        $this->label = $label;

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
     * @param string $picture
     * @return Storage
     */
    public function setPicture(string $picture): Storage
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return string
     */
    public function getPictureOld(): string
    {
        return $this->pictureOld;
    }

    /**
     * @param string $pictureOld
     * @return Storage
     */
    public function setPictureOld(string $pictureOld): Storage
    {
        $this->pictureOld = $pictureOld;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * @param mixed $lft
     * @return Storage
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * @param mixed $lvl
     * @return Storage
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * @param mixed $rgt
     * @return Storage
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param mixed $root
     * @return Storage
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     * @return Storage
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     * @return Storage
     */
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Storage
     */
    public function setUser(User $user): Storage
    {
        $this->user = $user;

        return $this;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add child.
     *
     * @param \AppBundle\Entity\Storage $child
     *
     * @return Storage
     */
    public function addChild(\AppBundle\Entity\Storage $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child.
     *
     * @param \AppBundle\Entity\Storage $child
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeChild(\AppBundle\Entity\Storage $child)
    {
        return $this->children->removeElement($child);
    }

    /**
     * Add product.
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return Storage
     */
    public function addProduct(\AppBundle\Entity\Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product.
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProduct(\AppBundle\Entity\Product $product)
    {
        return $this->products->removeElement($product);
    }

    /**
     * Get products.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Get products.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductsByUser(User $user)
    {
        $products = $this->getProducts();
        if($this->products->count() > 0){
            $products = $this->products->filter(function($product) use ($user){
                return $product->getUser() == $user;
            });
        }

        return $products;
    }
}
