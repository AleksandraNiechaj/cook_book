<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                // klucz tłumaczenia (możesz podmienić na swój)
                'label' => 'recipe.form.title',
                // zabezpiecza przed null → setTitle(string $title)
                'empty_data' => '',
                'attr' => ['maxlength' => 255],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'recipe.form.content',
                'empty_data' => '',
                'attr' => ['rows' => 8],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'recipe.form.choose_category',
                'label' => 'recipe.form.category',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
