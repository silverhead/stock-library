<?php

namespace AppBundle\Form;

use AppBundle\Form\Model\ProductListSorterModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductListSorterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productLabelOrder', ChoiceType::class, array(
                'required' => true,
                'choices' => array(
                    'Produit croissant' => 'ASC',
                    'Produit décroissant' => 'DESC',
                    'Produit non trié' => '',
                )
            ))
            ->add('categoryLabelOrder', ChoiceType::class, array(
                'required' => true,
                'choices' => array(
                    'categorie croissant' => 'ASC',
                    'categorie décroissant' => 'DESC',
                    'categorie non triée' => '',
                )
            ))
            ->add('storageLabelOrder', ChoiceType::class, array(
                'required' => true,
                'choices' => array(
                    'rangement croissant' => 'ASC',
                    'rangement décroissant' => 'DESC',
                    'rangement non triée' => '',
                )
            ))
            ->add('quantityOrder', ChoiceType::class, array(
                'required' => true,
                'choices' => array(
                    'quantité croissant' => 'ASC',
                    'quantité décroissant' => 'DESC',
                    'quantité non triée' => '',
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductListSorterModel::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_product_list_sorter_form';
    }
}
