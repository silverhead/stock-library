<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\Storage;
use AppBundle\Form\Model\ProductModel;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductModelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $optionCategories = array(
            'class' => 'AppBundle\Entity\Category',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                    ->addOrderBy('c.root', 'ASC')
                    ->addOrderBy('c.lft', 'ASC')
                    ;
            },
            'required' => false,
            'multiple' => true,
            'expanded' => false,
            'choice_value' => 'id',
            'choice_label' => function($cat){
                $label = "";
                for($i=0;$i < $cat->getLvl();$i++){
                    $label .= "-";
                }
                $label .= $cat->getLabel();
                return $label;
            }
        );
        if ($options['category']!=null){
            $optionCategories['data'] = array($options['category']);
        }

        $builder
            ->add('label')
            ->add('reference')
            ->add('description', TextareaType::class, array(
                'required' => false
            ))
            ->add('pictureFile', FileType::class, [
                'required' => false,
                'attr' => array(
                    'picturePath' => $options['picturePath']
                )
            ])
            ->add('keywords')
            ->add('quantity')
            ->add('quantityLimitAlert')
            ->add('categories', EntityType::class, $optionCategories)
            ->add('storage', EntityType::class, array(
                'class' => Storage::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->addOrderBy('s.root', 'ASC')
                        ->addOrderBy('s.lft', 'ASC')
                        ;
                },
                'choice_value' => 'id',
                'choice_label' => function($storage){
                    $label = "";
                    for($i=0;$i < $storage->getLvl();$i++){
                        $label .= "-";
                    }
                    $label .= $storage->getLabel();
                    return $label;
                },
                'multiple' => false,
                'expanded' => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'user',
            'picturePath',
            'category'
        ))
            ->setDefaults([
            'data_class' => ProductModel::class,
        ]);
    }
}
