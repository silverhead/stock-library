<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();

        $builder
            ->add('label')
            ->add('description')
            ->add('parent', EntityType::class, array(
                'class' => 'AppBundle\Entity\Category',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->addOrderBy('c.root', 'ASC')
                        ->addOrderBy('c.lft', 'ASC')
                        ;
                },
                'required' => false,
                'choice_value' => 'id',
                'choice_label' => function($cat){
                    $label = "";
                    for($i=0;$i < $cat->getLvl();$i++ ){
                        $label .= "-";
                    }
                    $label .= $cat->getLabel();
                    return $label;

                },
            ))
            ->add('pictureFile', FileType::class, [
                'required' => false,
                'attr' => array(
                    'picturePath' => $entity->getPictureWebPath()
                )
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
