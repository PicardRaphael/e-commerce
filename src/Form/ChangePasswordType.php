<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', EmailType::class, [
                'disabled' => true,
                'label' => 'Mon prénom'
            ])
            ->add('lastname', TextType::class, [
                'disabled' => true,
                'label' => 'Mon nom'
            ])
            ->add('email', TextType::class, [
                'disabled' => true,
                'label' => 'Mon adresse email'
            ])
            ->add('old_password', PasswordType::class, [
                'label' => 'Mon mot de passe',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre mot de passe actuel'
                ]
            ])
            ->add('new_password',  RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'constraints' => new Regex([
                    'pattern' => "/^(?=.{8,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/",
                    'message' => "Votre mot de passe doit contenir au minimum huit caractères, au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial"
                ]),
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identique',
                'label' => 'Mon nouveau mot de passe',
                'first_options' => ['label' => 'Mon nouveau mot de passe', 'attr' => [
                    'placeholder' => 'Veuillez saisir votre nouveau mot de passe'
                ]],
                'second_options' => ['label' => 'Confirmez votre nouveau mot de passe', 'attr' => [
                    'placeholder' => 'Veuillez confirmez votre nouveau mot de passe'
                ]]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Mettre à jour"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
