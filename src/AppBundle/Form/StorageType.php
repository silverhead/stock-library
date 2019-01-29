<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StorageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $entity = $builder->getData();

        $builder->add('label')
            ->add('parent', EntityTreeType::class, array(
                'class' => 'AppBundle\Entity\Storage',
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('s')
                        ->where("s.user = :user")->setParameter("user", $user)
                        ->orderBy('s.label', 'ASC');
                },
                'required' => false,
                'choice_value' => 'id',
                'choice_label' => 'label',
            ))
            ->add('pictureFile', FileType::class, array(
                'required' => false,
                'attr' => array(
                    'picturePath' => $entity->getPictureWebPath()
                )
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'user'
        ))
            ->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Storage'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_storage';
    }


}
