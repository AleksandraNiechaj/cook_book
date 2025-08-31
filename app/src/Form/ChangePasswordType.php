<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Change password form for authenticated users.
 */
final class ChangePasswordType extends AbstractType
{
    /**
     * Build change password form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'account.password.current',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new UserPassword(message: 'account.password.current_invalid'),
                ],
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => ['label' => 'account.password.new'],
                'second_options' => ['label' => 'account.password.new_repeat'],
                'invalid_message' => 'account.password.mismatch',
                'constraints' => [
                    new NotBlank(),
                    new Length(min: 6, max: 4096), // było 8 → 6
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
