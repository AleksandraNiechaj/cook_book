<?php

declare(strict_types=1);

/**
 * Formularz dodawania/edycji komentarzy.
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
 * Formularz dodawania/edycji komentarzy.
 */
final class CommentType extends AbstractType
{
    /**
     * Buduje formularz komentarza.
     *
     * @param FormBuilderInterface $builder obiekt budowniczego formularza
     * @param array<string,mixed>  $options opcje formularza
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
                    new NotNull(message: 'comment.validation.choose_rating'),
                ],
            ]);
    }

    /**
     * Konfiguruje opcje formularza.
     *
     * @param OptionsResolver $resolver obiekt konfiguratora opcji
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'csrf_protection' => true,
        ]);
    }
}
