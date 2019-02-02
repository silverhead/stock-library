<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="product_categories")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Category implements DocumentOwnerInterface
{
    use DocumentOwnerTrait;

    const PUBLIC_PATH = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web';
    const PICTURE_PATH = '/images/stock/categories';

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
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @var DocumentCategory
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\DocumentCategory", mappedBy="category")
     */
    private $documents;

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
     * @param string $label
     * @return Category
     */
    public function setLabel(string $label): Category
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
     * @return Category
     */
    public function setDescription(string $description): Category
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
     * @return Category
     */
    public function setPicture(string $picture = null): Category
    {
        $this->picture = $picture;

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
     * @return Category
     */
    public function setPictureFile(File $pictureFile): Category
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
     * @return mixed
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * @param mixed $lft
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * @return Category
     */
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->documents = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add child.
     *
     * @param \AppBundle\Entity\Category $child
     *
     * @return Category
     */
    public function addChild(\AppBundle\Entity\Category $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child.
     *
     * @param \AppBundle\Entity\Category $child
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeChild(\AppBundle\Entity\Category $child)
    {
        return $this->children->removeElement($child);
    }
}
