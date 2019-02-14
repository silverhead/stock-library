<?php

namespace AppBundle\Form\Handler;

use AppBundle\Entity\User;
use AppBundle\Form\Model\ProductListFilterModel;
use AppBundle\Form\ProductListFilterType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
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

    public function __construct(FormFactory $formFactory, EngineInterface $templating)
    {
        $this->formFactory = $formFactory;
        $this->templating = $templating;

        $this->setForm();
    }

    private function setForm()
    {
        $productListFilterModel = new ProductListFilterModel();

        $this->form = $this->formFactory->create(ProductListFilterType::class, $productListFilterModel);

        $this->model = $productListFilterModel;
    }

    public function handlerForm(Request $request)
    {
        $this->form->handleRequest($request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->model = $this->form->getData();

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

        return $criteria;
    }
}