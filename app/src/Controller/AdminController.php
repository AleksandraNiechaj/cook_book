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

/**
 * Kontroler panelu administracyjnego (własny profil admina).
 */
#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{
    /**
     * Dashboard admina.
     *
     * @return Response
     */
    #[Route('/admin', name: 'app_admin', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * Edycja swojego profilu (e-mail).
     *
     * @param Request                $request Żądanie
     * @param EntityManagerInterface $em      Menedżer encji
     *
     * @return Response
     */
    #[Route('/admin/profile', name: 'admin_profile', methods: ['GET', 'POST'])]
    public function editProfile(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Dane zostały zaktualizowane.');

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Zmiana swojego hasła (wymaga podania aktualnego).
     *
     * @param Request                     $request Żądanie
     * @param EntityManagerInterface      $em      Menedżer encji
     * @param UserPasswordHasherInterface $hasher  Hasher haseł
     *
     * @return Response
     */
    #[Route('/admin/change-password', name: 'admin_change_password', methods: ['GET', 'POST'])]
    public function changePassword(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): Response {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        // Używamy Twojego globalnego ChangePasswordType (z currentPassword + newPassword RepeatedType)
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $newPassword */
            $newPassword = (string) $form->get('newPassword')->getData();

            $user->setPassword($hasher->hashPassword($user, $newPassword));
            $em->flush();

            $this->addFlash('success', 'Hasło zostało zmienione.');

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
