<?php

namespace AppBundle\Entity;

use AppBundle\Form\Model\ProductModel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @Gedmo\Uploadable(pathMethod="getUploadRootPictureDir", filenameGenerator="SHA1")
 */
class Product
{
    use UploadPictureEntityTrait;

    const PICTURE_PATH = '/images/stock/products';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $label;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\UploadableFileName
     */
    private $picture;

    /**
     * @Assert\File()
     *
     */
    private $pictureFile;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $keywords;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Category")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Document", mappedBy="product")
     */
    private $documents;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="ProductByUser", mappedBy="product")
     */
    private $productByUser;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->productByUser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     * @return Product
     */
    public function setLabel($label): Product
    {
        $this->label = $label;

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
     * @return Product
     */
    public function setDescription(string $description): Product
    {
        $this->description = $description;

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
     * @return Product
     */
    public function setPicture(string $picture): Product
    {
        $this->picture = $picture;

        return $this;
    }

    public function getUploadPictureDir()
    {
        return self::PICTURE_PATH;
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
     * @return Product
     */
    public function setKeywords(string $keywords): Product
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param ArrayCollection $categories
     * @return Product
     */
    public function setCategories(ArrayCollection $categories): Product
    {
        $this->categories = $categories;

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
     * @return Product
     */
    public function setReference(string $reference): Product
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @param ArrayCollection $storage
     * @return Product
     */
    public function setStorage($storage): Product
    {
        $this->storage = $storage;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * @param mixed $documents
     * @return Product
     */
    public function setDocuments($documents)
    {
        $this->documents = $documents;

        return $this;
    }

    /**
     * Add category.
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Product
     */
    public function addCategory(\AppBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category.
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCategory(\AppBundle\Entity\Category $category)
    {
        return $this->categories->removeElement($category);
    }

    /**
     * Add document.
     *
     * @param \AppBundle\Entity\Document $document
     *
     * @return Product
     */
    public function addDocument(\AppBundle\Entity\Document $document)
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * Remove document.
     *
     * @param \AppBundle\Entity\Document $document
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDocument(\AppBundle\Entity\Document $document)
    {
        return $this->documents->removeElement($document);
    }


    /**
     * Add productUser.
     *
     * @param \AppBundle\Entity\ProductByUser $productUser
     *
     * @return Product
     */
    public function addProductByUser(\AppBundle\Entity\ProductByUser $productUser)
    {
        $this->productByUser[] = $productUser;

        return $this;
    }

    /**
     * Remove productUser.
     *
     * @param \AppBundle\Entity\ProductByUser $productByUser
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductByUser(\AppBundle\Entity\ProductByUser $productByUser)
    {
        return $this->productByUser->removeElement($productByUser);
    }

    /**
     * Get productUser.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductByUser()
    {
        return $this->productByUser;
    }

    public function getProductByUserFiltered(User $user)
    {
        $productByUser = new ArrayCollection();
        if($this->productByUser->count() > 0){
            $productByUser = $this->productByUser->filter(function($productByUser) use ($user){
                return $productByUser->getUser() == $user;
            });
        }

        return $productByUser;
    }

    public function setByProductModel(ProductModel $productModel)
    {
        $this->id = $productModel->getId();
        $this->label = $productModel->getLabel();
        $this->reference = $productModel->getReference();
        $this->description = $productModel->getDescription();
        $this->keywords = $productModel->getKeywords();
        $this->categories = $productModel->getCategories();

        $this->pictureFile = $productModel->getPictureFile();
    }
}
