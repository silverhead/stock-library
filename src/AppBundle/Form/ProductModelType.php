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
        $user = $options['user'];

        $builder
            ->add('label')
            ->add('reference')
            ->add('description', TextareaType::class)
            ->add('pictureFile', FileType::class, [
                'required' => false,
                'attr' => array(
                    'picturePath' => $options['picturePath']
                )
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
                'query_builder' => function (EntityRepository $er2) use ($user) {
                    return $er2->createQueryBuilder('s')
                        ->where("s.user = :user")->setParameter("user", $user)
                        ->orderBy('s.label', 'ASC');
                },
                'choice_value' => 'id',
                'choice_label' => 'label',
                'multiple' => false,
                'expanded' => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'user',
            'picturePath'
        ))
            ->setDefaults([
            'data_class' => ProductModel::class,
        ]);
    }
}
