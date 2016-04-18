<?php
/**
 * @author Gerard van Helden <gerard@zicht.nl>
 * @copyright Zicht Online <http://zicht.nl>
 */
namespace Zicht\Bundle\HtmldevBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExampleType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'forms'
        ]);
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('htmldev_text', 'text')
            ->add('htmldev_email', 'email')
            ->add('htmldev_password', 'password')
            ->add('htmldev_radio_buttons', 'choice', [
                'expanded' => true,
                'choices' => [
                    'choice-1' => 'Choice number 1',
                    'choice-2' => 'Choice number 2',
                    'choice-3' => 'Choice number 3',
                ]
            ])
            ->add('htmldev_checkbox_buttons', 'choice', [
                'expanded' => true,
                'multiple' => true,
                'choices' => [
                    'choice-1' => 'Choice number 1',
                    'choice-2' => 'Choice number 2',
                    'choice-3' => 'Choice number 3',
                ]
            ])
            ->add('htmldev_select_dropdown', 'choice', [
                'expanded' => false,
                'multiple' => false,
                'choices' => [
                    'choice-1' => 'Choice number 1',
                    'choice-2' => 'Choice number 2',
                    'choice-3' => 'Choice number 3',
                ]
            ])
            ->add('htmldev_checkbox', 'checkbox')
            ->add('htmldev_birthdate', 'birthday')
        ;
    }


    public function getName()
    {
        return 'htmldev_example';
    }
}