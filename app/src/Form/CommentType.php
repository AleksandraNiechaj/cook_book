<?php

declare(strict_types=1);

/**
 * This file is part of the Cook Book project.
 * (c) 2025 Aleksandra Niechaj
 * License: For educational purposes (course project).
 */

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Formularz komentarza (dla zalogowanych): treść + ocena 1–5.
 */
final class CommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder builder formularza
     * @param array<string,mixed>  $options opcje formularza
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'comment.form.content',
                'empty_data' => '',
                'attr' => ['rows' => 4],
            ])
            ->add('rating', ChoiceType::class, [
                'label' => 'comment.form.rating',
                'placeholder' => 'comment.form.choose_rating',
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
                'required' => true,
                'constraints' => [
                    new NotNull(message: 'Please choose a rating.'),
                ],
            ]);
    }

    /**
     * @param OptionsResolver $resolver resolver opcji
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'csrf_protection' => true,
        ]);
    }
}
