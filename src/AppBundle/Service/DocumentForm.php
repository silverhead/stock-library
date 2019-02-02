<?php

namespace AppBundle\Service;


use AppBundle\Entity\AbstractDocument;
use AppBundle\Entity\DocumentOwnerInterface;
use AppBundle\Form\DocumentType;
use Doctrine\ORM\EntityManagerInterface;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;

class DocumentForm
{
    /**
     * @var FormFactory
     */
    private $formFactory;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var AbstractDocument
     */
    private $document;

    /**
     * @var UploadableManager
     */
    private $uploadableManager;

    public function __construct(FormFactory $formFactory, EntityManagerInterface $entityManager, UploadableManager $uploadableManager, EngineInterface $templating)
    {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->templating = $templating;
        $this->uploadableManager = $uploadableManager;
    }

    public function setForm(AbstractDocument $document)
    {
        $this->form = $this->formFactory->create(DocumentType::class, $document);

        $this->document = $document;
    }

    public function handlerForm(Request $request, DocumentOwnerInterface $owner)
    {
        $this->form->handleRequest($request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            if ($this->document->getFile() instanceof UploadedFile) {
                $this->uploadableManager ->markEntityToUpload($this->document, $this->document->getFile());
            }

            $this->document->setOwner($owner);
            $em = $this->entityManager;
            $em->persist($this->document);
            $em->flush();

            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getAddButton()
    {
        return $this->templating->render('document/addButton.html.twig');
    }

    /**
     * @return string
     */
    public function getModalDocumentForm()
    {
        return $this->templating->render('document/modalForm.html.twig', array(
            'form' => $this->form->createView(),
        ));
    }
}