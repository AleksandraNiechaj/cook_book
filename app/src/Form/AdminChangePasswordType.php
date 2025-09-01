<?php

declare(strict_types=1);

/**
 * This file is part of the Cook Book project.
 *
 * PHP version 8.3
 *
 * @author    Aleksandra Niechaj
 *
 * @copyright 2025
 *
 * @license   For educational purposes (course project).
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Formularz zmiany hasła użytkownika przez admina (bez podawania starego hasła).
 */
final class AdminChangePasswordType extends AbstractType
{
    /**
     * Build form.
     *
     * @param FormBuilderInterface $builder The builder
     * @param array<string, mixed> $options The options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('newPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'mapped' => false,
            'first_options' => ['label' => 'Nowe hasło'],
            'second_options' => ['label' => 'Powtórz nowe hasło'],
            'invalid_message' => 'Hasła nie są identyczne.',
            'constraints' => [
                new NotBlank(),
                new Length(min: 6, max: 4096),
            ],
        ]);
    }

    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver The resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
        ]);
    }
}
