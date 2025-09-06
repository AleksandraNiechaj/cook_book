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

use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formularz tagu.
 */
final class TagType extends AbstractType
{
    /**
     * Build tag form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nazwa',
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug',
                'help' => 'Małe litery, cyfry, myślniki',
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
            'data_class' => Tag::class,
            'csrf_protection' => true,
        ]);
    }
}
