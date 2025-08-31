<?php

declare(strict_types=1);

/**
 * This file is part of the Cook Book project.
 * (c) 2025 Aleksandra Niechaj
 * License: For educational purposes (course project).
 */

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formularz do tworzenia i edycji przepisów.
 */
class RecipeType extends AbstractType
{
    /**
     * Buduje formularz przepisu.
     *
     * @param FormBuilderInterface $builder Builder formularza.
     * @param array<string,mixed>  $options Opcje formularza.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'recipe.form.title',
                'empty_data' => '',
            ])
            ->add('content', TextareaType::class, [
                'label' => 'recipe.form.content',
                'empty_data' => '',
                'attr' => ['rows' => 6],
            ]);
    }

    /**
     * Konfiguracja domyślnych opcji.
     *
     * @param OptionsResolver $resolver Resolver opcji.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
