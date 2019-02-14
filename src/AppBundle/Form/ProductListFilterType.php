<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\Storage;
use AppBundle\Form\Model\ProductListFilterModel;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductListFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('label', TextType::class, array(
                'required' => false
            ))
            ->add('reference', TextType::class, array(
                'required' => false
            ))
            ->add('categories', EntityType::class, array(
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c');
                },
                'required' => false,
                'choice_value' => 'id',
                'choice_label' => 'label',
                'multiple' => true,
                'expanded' => false
            ))
            ->add('storage', EntityType::class, array(
                'class' => Storage::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s');
                },
                'required' => false,
                'choice_value' => 'id',
                'choice_label' => 'label',
                'multiple' => true,
                'expanded' => false
            ))
            ->add('haveProductOnly', ChoiceType::class, array(
                'choices' => array(
                    'Oui' => 1,
                    'Non' => 0
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductListFilterModel::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_product_list_filter_form';
    }
}
