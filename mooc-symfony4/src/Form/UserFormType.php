<?php

namespace App\Form;

use App\Entity\Sex;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends UserFirstFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder,$options);
        $builder
            ->add('forename' ,null ,['label' => 'Prénom'])
            ->add('name' ,null ,['label' => 'Nom'])
            ->add('mail',EmailType::class ,['label' => 'Mail'])
            ->add('birth',DateType::class ,['label' => 'Date de naissance'])
            ->add('password',PasswordType::class ,['label' => 'Mot de passe'])
            ->add('places',IntegerType::class ,['label' => 'Code postal'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
