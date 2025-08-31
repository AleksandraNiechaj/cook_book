<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Category::class]);
    }
}
