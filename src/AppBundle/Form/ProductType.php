<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\Storage;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label')
            ->add('reference')
            ->add('description')
            ->add('pictureFile', FileType::class, [
                'required' => false
            ])
            ->add('keywords')
            ->add('quantity')
            ->add('quantityLimitAlert')
            ->add('categories', EntityTreeType::class, array(
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
                'query_builder' => function (EntityRepository $er2) {
                    return $er2->createQueryBuilder('s')
                        ->orderBy('s.label', 'ASC');
                },
                'choice_value' => 'id',
                'choice_label' => 'label',
                'multiple' => false,
                'expanded' => false
            ))
//            ->add('documents', CollectionType::class, array(
//                    'entry_type' => DocumentType::class,
//                    'entry_options' => ['label' => false],
//            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
