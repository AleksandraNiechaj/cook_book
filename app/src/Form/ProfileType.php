<?php

declare(strict_types=1);

/**
 * This file is part of the Cook Book project.
 * (c) 2025 Aleksandra Niechaj
 * License: For educational purposes (course project).
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formularz do edycji profilu użytkownika.
 */
class ProfileType extends AbstractType
{
    /**
     * Buduje formularz profilu.
     *
     * @param FormBuilderInterface $builder Builder formularza.
     * @param array<string,mixed>  $options Opcje formularza.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'profile.form.email',
            ])
            ->add('name', TextType::class, [
                'label' => 'profile.form.name',
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
