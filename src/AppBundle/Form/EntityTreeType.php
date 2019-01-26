<?php
/**
 * (c) Masoud Zohrabi <mdzzohrabi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EntityTreeType
 * @package Mdzzohrabi\Form
 */
class EntityTreeType extends AbstractType {

    public function buildView( FormView $view , FormInterface $form , array $options ) {

        $choices = [];

        foreach ( $view->vars['choices'] as $choice ) {
            if ( $choice->data->getParent() === null )
                $choices[ $choice->value ] = $choice->data;
        }

        $choices = $this->buildTreeChoices( $choices );

        $view->vars['choices'] = $choices;

    }

    /**
     * @param object[] $choices
     * @param int $level
     * @return array
     */
    protected function buildTreeChoices( $choices , $level = 0 ) {

        $result = array();

        foreach ( $choices as $choice ){

            $result[ $choice->getLft() ] = new ChoiceView(
                $choice,
                (string)$choice->getId(),
                str_repeat( '--' , $level ) . ' ' . $choice->getLabel(),
                []
            );

            if ( !$choice->getChildren()->isEmpty() )
                $result = array_merge(
                    $result,
                    $this->buildTreeChoices( $choice->getChildren() , $level + 1 )
                );

        }

        return $result;

    }

    public function getParent() {
        return EntityType::class;
    }

}