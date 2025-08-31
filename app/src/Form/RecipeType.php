<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Form type for creating and editing recipes.
 */
final class RecipeType extends AbstractType
{
    /**
     * Build recipe form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options The form options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'recipe.form.title',
                'constraints' => [
                    new NotBlank(),
                    new Length(min: 3, max: 255),
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'recipe.form.content',
                'attr' => ['rows' => 8],
                'constraints' => [
                    new NotBlank(),
                    new Length(min: 10),
                ],
            ])
            ->add('category', EntityType::class, [
                'label' => 'recipe.form.category',
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'recipe.form.category_placeholder',
                'required' => true,
                'query_builder' => static function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
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
            'data_class' => Recipe::class,
            'csrf_protection' => true,
        ]);
    }
}
