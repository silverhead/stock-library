<?php

namespace AppBundle\Form\Handler;

use AppBundle\Entity\User;
use AppBundle\Form\Model\ProductListFilterModel;
use AppBundle\Form\ProductListFilterType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Templating\EngineInterface;

class ProductListFilterFormHandler
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
     * @var ProductListFilterModel
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
        $productListFilterModel = $this->getDataFromSession();

        $this->form = $this->formFactory->create(ProductListFilterType::class, $productListFilterModel);

        $this->model = $productListFilterModel;
    }

    private function getDataFromSession()
    {
        return unserialize($this->session->get('app_product_list_filter', serialize(new ProductListFilterModel())));
    }

    public function handlerForm(Request $request)
    {
        $this->form->handleRequest($request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->model = $this->form->getData();

            $this->session->set('app_product_list_filter', serialize($this->model));

            return true;
        }

        return false;
    }

    public function renderForm()
    {
        return $this->templating->render('product/formFilter.html.twig', array(
            'form' => $this->form->createView()
        ));
    }

    public function getCriteria(User $user)
    {
        $criteria = array();

        if ($this->model->haveProductOnly()){
            $criteria['productByUser.user'] = (object) array(
                'operator' => 'equal',
                'search' => $user->getId()
            );
        }

        if ($this->model->getLabel() !=""){
            $criteria['p.label'] = (object) array(
                'operator' => '%like%',
                'search' => $this->model->getLabel()
            );
        }

        if ($this->model->getReference() !=""){
            $criteria['p.reference'] = (object) array(
                'operator' => 'like%',
                'search' => $this->model->getReference()
            );
        }

        if (null !== $this->model->getCategories() && $this->model->getCategories()->count() > 0){
            $criteria['category.id'] = (object) array(
                'operator' => 'allOfCat',
                'search' => $this->model->getCategories()
            );
        }

        if (null !== $this->model->getStorage() && $this->model->getStorage()->count() > 0){
            $criteria['productByUser.storage'] = (object) array(
                'operator' => 'allOfStorage',
                'search' => $this->model->getStorage()
            );
        }


        return $criteria;
    }
}