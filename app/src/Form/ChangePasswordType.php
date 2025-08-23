<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Aktualne hasło',
                'mapped' => false,
                'required' => true,
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'Nowe hasło',
                'mapped' => false,
                'required' => true,
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Powtórz nowe hasło',
                'mapped' => false,
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
