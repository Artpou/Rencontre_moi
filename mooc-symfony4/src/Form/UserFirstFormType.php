<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFirstFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('sex', EntityType::class, array(
                'class'        => 'App\Entity\Sex',
                'choice_label' => 'name',
                'multiple'     => false, 'expanded' => true,
                'label_attr'=>[
                    'class'=>'radio-inline'
                ],
                'label' => 'Je suis'
              ))
              ->add('preference', EntityType::class, array(
                'class'        => 'App\Entity\Preference',
                'choice_label' => 'name',
                'multiple'     => false, 'expanded' => true,
                'label_attr'=>[
                    'class'=>'radio-inline'
                ],
                'label' => 'Je recherche'
              ))
            ->add('Submit', SubmitType::class, ['label' => 'Continuer'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
