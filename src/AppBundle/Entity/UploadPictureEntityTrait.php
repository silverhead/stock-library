<?php

namespace AppBundle\Entity;


use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\HasLifecycleCallbacks
 */
trait UploadPictureEntityTrait
{
    /**
     * @var File
     */
    private $pictureFile;

    /**
     * @var string
     */
    private $pictureOld;

    abstract public function getPicture();
    abstract public function setPicture();
    abstract public function getUploadPictureDir();

    /**
     * @return UploadedFile
     */
    public function getPictureFile(): ?UploadedFile
    {
        return $this->pictureFile;
    }

    /**
     * @param File $pictureFile
     * @return Category
     */
    public function setPictureFile(UploadedFile $pictureFile): self
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
        dump($this->getPictureFile());

        if (null !== $this->getPictureFile()) {
            $this->setPictureOld($this->getPicture());
            $filename = sha1(uniqid(mt_rand(), true));
            $this->setPicture($filename.'.'.$this->getPictureFile()->guessExtension());
        }
    }

    public function upload()
    {
        if (null === $this->getPictureFile()) {
            return;
        }

        if (null !== $this->getPictureOld() && '' !== $this->getPictureOld()) {
            if (is_file($this->getUploadRootPictureDir().'/'.$this->getPictureOld())){
                // delete the old avatar
                unlink($this->getUploadRootPictureDir().'/'.$this->getPictureOld);
                // clear the temp image path
                $this->setPictureOld(null);
            }
        }

        $this->getPictureFile()->move($this->getUploadRootPictureDir(), $this->getPicture());

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
        if (null === $this->getPicture() || '' === $this->getPicture()){
            return null;
        }
        else{
            return $this->getUploadRootPictureDir().'/'.$this->getPicture();
        }
    }

    public function getPictureWebPath()
    {
        if (null === $this->getPicture() || '' === $this->getPicture()){
            return null;
        }
        else{
            return $this->getUploadPictureDir().DIRECTORY_SEPARATOR.$this->getPicture();
        }
    }

    protected function getUploadRootPictureDir()
    {
        return $this->getWebPath().$this->getUploadPictureDir();
    }

    private function getWebPath()
    {
        return __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web';
    }
}