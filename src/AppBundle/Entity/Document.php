<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Document
 *
 * @ORM\Table(name="document")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DocumentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Document
{
    const PUBLIC_PATH = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web';
    const FILE_PATH = '/documents';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $fileName;

    /**
     * @var string
     */
    private $fileNameOld;

    /**
     * @var UploadedFile
     */
    private $file;

    /**
     * @var Product
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="documents")
     */
    private $product;

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
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Document
     */
    public function setTitle(string $title): Document
    {
        $this->title = $title;

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
     * @return Document
     */
    public function setDescription(string $description): Document
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     * @return Document
     */
    public function setFileName(string $fileName): Document
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return Document
     */
    public function setProduct(Product $product): Document
    {
        $this->product = $product;

        return $this;
    }



    /**
     * @return File
     */
    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    /**
     * @param File $file
     * @return Document
     */
    public function setFile(UploadedFile $file): Document
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreFlush()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $this->fileOld = $this->fileName;
            $filename = sha1(uniqid(mt_rand(), true));
            $this->fileName = $filename.'.'.$this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        if (null !== $this->fileNameOld && '' !== $this->fileNameOld) {
            if (is_file($this->getUploadRootPictureDir().'/'.$this->fileNameOld)){
                unlink($this->getUploadRootPictureDir().'/'.$this->fileNameOld);
                $this->fileNameOld = null;
            }
        }

        $this->getFile()->move($this->getUploadRootDir(), $this->file);

        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsoluteFilePath()) {
            unlink($file);
        }
    }

    public function getAbsoluteFilePath()
    {
        if (null === $this->file || '' === $this->file){
            return null;
        }
        else{
            return $this->getUploadRootFileDir().'/'.$this->file;
        }
    }

    public function getFileWebPath()
    {
        if (null === $this->file || '' === $this->file){
            return null;
        }
        else{
            return $this->getUploadFileDir().DIRECTORY_SEPARATOR.$this->file;
        }
    }

    protected function getUploadRootFileDir()
    {
        return self::PUBLIC_PATH.$this->getUploadFileDir();
    }

    protected function getUploadFileDir()
    {
        return self::FILE_PATH;
    }
}
