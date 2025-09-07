<?php

declare(strict_types=1);

/**
 * Kontroler odpowiedzialny za operacje na koncie użytkownika.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\ProfileType;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Kontroler zarządzania kontem użytkownika (edycja profilu i hasła).
 */
#[IsGranted('ROLE_USER')]
final class AccountController extends AbstractController
{
    /**
     * Konstruktor.
     *
     * @param UserServiceInterface        $userService serwis użytkowników
     * @param UserPasswordHasherInterface $hasher      hasher haseł
     */
    public function __construct(private readonly UserServiceInterface $userService, private readonly UserPasswordHasherInterface $hasher)
    {
    }

    /**
     * Edycja profilu użytkownika.
     *
     * @param Request             $request    obiekt żądania
     * @param TranslatorInterface $translator tłumacz komunikatów
     *
     * @return array Result
     */
    #[Route(path: '/account/profile', name: 'account_profile_edit', methods: ['GET', 'POST'])]
    public function editProfile(Request $request, TranslatorInterface $translator): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);

            $this->addFlash('success', $translator->trans('flash.profile_updated'));

            return $this->redirectToRoute('account_profile_edit');
        }

        return $this->render('account/profile_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Zmiana hasła użytkownika.
     *
     * @param Request             $request    obiekt żądania
     * @param TranslatorInterface $translator tłumacz komunikatów
     *
     * @return array Result
     */
    #[Route(path: '/account/password', name: 'account_password_change', methods: ['GET', 'POST'])]
    public function changePassword(Request $request, TranslatorInterface $translator): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $newPassword */
            $newPassword = (string) $form->get('newPassword')->getData();

            $this->userService->changePassword($user, $newPassword, $this->hasher);

            $this->addFlash('success', $translator->trans('flash.password_changed'));

            return $this->redirectToRoute('account_password_change');
        }

        return $this->render('account/password_change.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
