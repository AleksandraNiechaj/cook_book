<?php

declare(strict_types=1);

/**
 * Admin: zarządzanie kontami użytkowników.
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
 * Kontroler administracyjny do zarządzania użytkownikami.
 */
#[IsGranted('ROLE_ADMIN')]
final class AdminUserController extends AbstractController
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
     * Lista użytkowników z paginacją i sortowaniem.
     *
     * @param Request $request obiekt żądania
     *
     * @return Response
     */
    #[Route('/admin/users', name: 'admin_user_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = 10;

        $sort = (string) $request->query->get('sort', 'email');
        $dir = strtoupper((string) $request->query->get('dir', 'ASC'));

        $pagination = $this->userService->getPaginatedList($page, $limit, $sort, $dir);

        return $this->render('admin/user_index.html.twig', [
            'items' => $pagination['items'],
            'page' => $page,
            'pages' => $pagination['pages'],
            'total' => $pagination['total'],
            'sort' => $pagination['sort'],
            'dir' => $pagination['dir'],
            'limit' => $limit,
        ]);
    }

    /**
     * Edycja danych użytkownika (email, role).
     *
     * @param Request $request obiekt żądania
     * @param User    $user    encja użytkownika
     *
     * @return Response
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
     *
     * @param Request $request obiekt żądania
     * @param User    $user    encja użytkownika
     *
     * @return Response
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
