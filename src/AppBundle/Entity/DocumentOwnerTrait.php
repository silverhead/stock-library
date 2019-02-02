<?php

namespace AppBundle\Entity;

/**
 * Trait DocumentOwnerTrait
 */
trait DocumentOwnerTrait
{
    /**
     * Add document.
     *
     * @param \AppBundle\Entity\AbstractDocument $document
     *
     * @return Product
     */
    public function addDocument(AbstractDocument $document)
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * Remove document.
     *
     * @param \AppBundle\Entity\AbstractDocument $document
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDocument(AbstractDocument $document)
    {
        return $this->documents->removeElement($document);
    }

    /**
     * @return mixed
     */
    public function getDocuments()
    {
        return $this->documents;
    }
}