<?php

declare(strict_types=1);

/**
 * This file is part of the Cook Book project.
 * (c) 2025 Aleksandra Niechaj
 * License: For educational purposes (course project).
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formularz do zmiany hasła użytkownika.
 */
class ChangePasswordType extends AbstractType
{
    /**
     * Buduje formularz zmiany hasła.
     *
     * @param FormBuilderInterface $builder Builder formularza.
     * @param array<string,mixed>  $options Opcje formularza.
     */
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

    /**
     * Konfiguracja domyślnych opcji.
     *
     * @param OptionsResolver $resolver Resolver opcji.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
