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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formularz do dodawania komentarzy.
 */
class CommentType extends AbstractType
{
    /**
     * Buduje formularz komentarza.
     *
     * @param FormBuilderInterface $builder Builder formularza.
     * @param array<string,mixed>  $options Opcje formularza.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('authorName', TextType::class, [
                'label' => 'comment.form.name',
                'empty_data' => '',
                'attr' => ['maxlength' => 100],
            ])
            ->add('authorEmail', EmailType::class, [
                'label' => 'comment.form.email',
                'empty_data' => '',
                'attr' => ['maxlength' => 180],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'comment.form.content',
                'empty_data' => '',
                'attr' => ['rows' => 4],
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
            'data_class' => Comment::class,
        ]);
    }
}
