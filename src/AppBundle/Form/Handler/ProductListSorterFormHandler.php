<?php

namespace AppBundle\Form\Handler;

use AppBundle\Entity\User;
use AppBundle\Form\Model\ProductListSorterModel;
use AppBundle\Form\ProductListSorterType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Templating\EngineInterface;

class ProductListSorterFormHandler
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var ProductListSorterModel
     */
    private $model;
    /**
     * @var \Session
     */
    private $session;

    /**
     * ProductListFilterFormHandler constructor.
     * @param FormFactory $formFactory
     * @param EngineInterface $templating
     * @param \SessionHandlerInterface $session
     */
    public function __construct(FormFactory $formFactory, EngineInterface $templating, Session $session)
    {
        $this->formFactory = $formFactory;
        $this->templating = $templating;
        $this->session = $session;

        $this->setForm();
    }

    private function setForm()
    {
        $productListSorterModel = $this->getDataFromSession();

        $this->form = $this->formFactory->create(ProductListSorterType::class, $productListSorterModel);

        $this->model = $productListSorterModel;
    }

    private function getDataFromSession()
    {
        $model = new ProductListSorterModel();
        //Set default value to product label ASC
        $model->setProductLabelOrder('ASC');

        return unserialize($this->session->get('app_product_list_sorter', serialize($model)));
    }

    public function handlerForm(Request $request)
    {
        $this->form->handleRequest($request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->model = $this->form->getData();

            $this->session->set('app_product_list_sorter', serialize($this->model));

            return true;
        }

        return false;
    }

    public function renderForm()
    {
        return $this->templating->render('product/formSorter.html.twig', array(
            'form' => $this->form->createView()
        ));
    }

    public function getSorter()
    {
        $sorter = array();

        $productLabelOrder = $this->model->getProductLabelOrder();
        if ($productLabelOrder != ''){
            $sorter['p.label'] =  $productLabelOrder;
        }

        $categoryLabelOrder = $this->model->getCategoryLabelOrder();
        if ($categoryLabelOrder != ''){
            $sorter['category.label'] =  $categoryLabelOrder;
        }

        $storageLabelOrder = $this->model->getStorageLabelOrder();
        if ($storageLabelOrder != ''){
            $sorter['storage.label'] =  $storageLabelOrder;
        }

        $quantityLabelOrder = $this->model->getQuantityOrder();
        if ($quantityLabelOrder != ''){
            $sorter['productByUser.quantity'] =  $quantityLabelOrder;
        }

        return $sorter;
    }
}