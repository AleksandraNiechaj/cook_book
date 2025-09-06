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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Registration form type (simple DTO, no data_class).
 */
final class RegistrationType extends AbstractType
{
    /**
     * Build registration form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'auth.register.email',
                'constraints' => [
                    new NotBlank(),
                    new EmailConstraint(),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => ['label' => 'auth.register.password'],
                'second_options' => ['label' => 'auth.register.password_repeat'],
                'invalid_message' => 'auth.register.password_mismatch',
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
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
        ]);
    }
}
