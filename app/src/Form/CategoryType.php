<?php

declare(strict_types=1);

/**
 * This file is part of the Cook Book project.
 * (c) 2025 Aleksandra Niechaj
 * License: For educational purposes (course project).
 */

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formularz do tworzenia i edycji kategorii.
 */
class CategoryType extends AbstractType
{
    /**
     * Buduje formularz kategorii.
     *
     * @param FormBuilderInterface $builder Builder formularza.
     * @param array<string,mixed>  $options Opcje formularza.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'category.form.name',
                'empty_data' => '',
                'attr' => ['maxlength' => 100],
            ])
            ->add('slug', TextType::class, [
                'label' => 'category.form.slug',
                'empty_data' => '',
                'attr' => ['maxlength' => 100, 'pattern' => '[a-z0-9]+(?:-[a-z0-9]+)*'],
                'help' => 'category.form.slug_help',
            ]);
    }

    /**
     * Konfiguracja domyślnych opcji.
     *
     * @param OptionsResolver $resolver Resolver opcji.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Category::class]);
    }
}
