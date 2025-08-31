<?php

declare(strict_types=1);

/**
 * This file is part of the Cook Book project.
 *
 * PHP version 8.3
 *
 * @author    Aleksandra Niechaj <aleksandra.niechaj@example.com>
 *
 * @copyright 2025 Aleksandra Niechaj
 *
 * @license   For educational purposes (course project).
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Account (profile & password) controller for authenticated users.
 */
#[IsGranted('ROLE_USER')]
final class AccountController extends AbstractController
{
    /**
     * Edit current user's profile.
     *
     * @param Request                $request    The current request
     * @param EntityManagerInterface $em         The entity manager
     * @param TranslatorInterface    $translator The translator
     */
    #[Route(path: '/account/profile', name: 'account_profile_edit', methods: ['GET', 'POST'])]
    public function editProfile(
        Request $request,
        EntityManagerInterface $em,
        TranslatorInterface $translator,
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', $translator->trans('flash.profile_updated'));

            return $this->redirectToRoute('account_profile_edit');
        }

        return $this->render('account/profile_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Change current user's password.
     *
     * @param Request                     $request    The current request
     * @param EntityManagerInterface      $em         The entity manager
     * @param UserPasswordHasherInterface $hasher     The password hasher
     * @param TranslatorInterface         $translator The translator
     */
    #[Route(path: '/account/password', name: 'account_password_change', methods: ['GET', 'POST'])]
    public function changePassword(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
        TranslatorInterface $translator,
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $newPassword */
            $newPassword = (string) $form->get('newPassword')->getData();

            // currentPassword jest weryfikowane przez constraint UserPassword w samym formularzu
            $user->setPassword($hasher->hashPassword($user, $newPassword));
            $em->flush();

            $this->addFlash('success', $translator->trans('flash.password_changed'));

            return $this->redirectToRoute('account_password_change');
        }

        return $this->render('account/password_change.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
