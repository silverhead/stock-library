<?php

namespace AppBundle\Entity;

Interface DocumentOwnerInterface
{
    public function addDocument(AbstractDocument $document);

    public function removeDocument(AbstractDocument $document);

    public function getDocuments();
}