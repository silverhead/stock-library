<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class AbstractDocument
 * @package AppBundle\Entity
 * @ORM\MappedSuperclass()
 * @Gedmo\Uploadable(pathMethod="getUploadRootFileDir", filenameGenerator="SHA1")
 */
Abstract class AbstractDocument
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
     * @Gedmo\UploadableFileName()
     */
    private $fileName;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\UploadableFileMimeType()
     */
    private $fileType;

    /**
     * @var string
     */
    private $fileNameOld;

    /**
     * @var UploadedFile
     */
    private $file;

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
     * @return DocumentProduct
     */
    public function setTitle(string $title): AbstractDocument
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
     * @return DocumentProduct
     */
    public function setDescription(string $description): AbstractDocument
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
     * @return DocumentProduct
     */
    public function setFileName(string $fileName): AbstractDocument
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFileType(): ?string
    {
        return $this->fileType;
    }

    /**
     * @param string $fileType
     * @return DocumentProduct
     */
    public function setFileType(string $fileType): AbstractDocument
    {
        $this->fileType = $fileType;

        return $this;
    }

    /**
     * @return File
     */
    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file): AbstractDocument
    {
        $this->file = $file;

        return $this;
    }

//    /**
//     * @ORM\PrePersist()
//     * @ORM\PreFlush()
//     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $this->fileOld = $this->fileName;
            $filename = sha1(uniqid(mt_rand(), true));
            $this->fileName = $filename.'.'.$this->getFile()->guessExtension();
        }
    }

    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        if (null !== $this->fileNameOld && '' !== $this->fileNameOld) {
            if (is_file($this->getUploadRootFileDir().'/'.$this->fileNameOld)){
                unlink($this->getUploadRootFileDir().'/'.$this->fileNameOld);
                $this->fileNameOld = null;
            }
        }

        $this->getFile()->move($this->getUploadRootFileDir(), $this->fileName);

        $this->file = null;
    }

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
        if (null === $this->fileName || '' === $this->fileName){
            return null;
        }
        else{
            return $this->getUploadFileDir().DIRECTORY_SEPARATOR.$this->fileName;
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

    public function getCommonFileType()
    {
        $fileTypeCommon = 'unknown';

        if (preg_match('/image/', $this->fileType)){
            $fileTypeCommon = 'image';
        }
        else if(preg_match('/text/', $this->fileType))
        {
            $fileTypeCommon = 'text';
        }
        else if(preg_match('/pdf/',$this->fileType))
        {
            $fileTypeCommon = 'pdf';
        }

        return $fileTypeCommon;
    }

    /**
     * @param DocumentOwnerInterface $owner
     * @return AbstractDocument
     */
    public abstract function setOwner(DocumentOwnerInterface $owner): AbstractDocument;
}