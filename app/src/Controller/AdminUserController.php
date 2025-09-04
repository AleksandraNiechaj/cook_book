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
use App\Form\AdminChangePasswordType;
use App\Form\AdminUserType;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Admin: zarządzanie kontami użytkowników.
 */
#[IsGranted('ROLE_ADMIN')]
final class AdminUserController extends AbstractController
{
    public function __construct(
        private readonly UserServiceInterface $userService,
        private readonly UserPasswordHasherInterface $hasher,
    ) {
    }

    /**
     * Lista użytkowników z paginacją i sortowaniem.
     */
    #[Route('/admin/users', name: 'admin_user_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page  = max(1, (int) $request->query->get('page', 1));
        $limit = 10;

        $sort = (string) $request->query->get('sort', 'email');
        $dir  = strtoupper((string) $request->query->get('dir', 'ASC'));

        $pagination = $this->userService->getPaginatedList($page, $limit, $sort, $dir);

        return $this->render('admin/user_index.html.twig', [
            'items' => $pagination['items'],
            'page'  => $page,
            'pages' => $pagination['pages'],
            'total' => $pagination['total'],
            'sort'  => $pagination['sort'],
            'dir'   => $pagination['dir'],
            'limit' => $limit,
        ]);
    }

    /**
     * Edycja danych użytkownika (email, role).
     */
    #[Route('/admin/users/{id}/edit', name: 'admin_user_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);
            $this->addFlash('success', 'Dane użytkownika zostały zapisane.');

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user_edit.html.twig', [
            'form' => $form->createView(),
            'item' => $user,
        ]);
    }

    /**
     * Zmiana hasła użytkownika przez admina (bez wymagania starego hasła).
     */
    #[Route('/admin/users/{id}/password', name: 'admin_user_password', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function changePassword(Request $request, User $user): Response
    {
        $form = $this->createForm(AdminChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $newPassword */
            $newPassword = (string) $form->get('newPassword')->getData();

            $this->userService->changePassword($user, $newPassword, $this->hasher);

            $this->addFlash('success', 'Hasło użytkownika zostało zmienione.');

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user_password.html.twig', [
            'form' => $form->createView(),
            'item' => $user,
        ]);
    }
}
