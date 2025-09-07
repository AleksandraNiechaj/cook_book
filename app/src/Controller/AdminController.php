<?php

declare(strict_types=1);

/**
 * Kontroler administracyjny – obsługuje profil i hasło administratora.
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

/**
 * Kontroler administracyjny – obsługuje profil i hasło administratora.
 */
#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
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
     * Strona główna panelu admina.
     *
     * @return Response odpowiedź z widokiem panelu admina
     */
    #[Route('/admin', name: 'app_admin', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * Edycja profilu administratora.
     *
     * @param Request $request obiekt żądania
     *
     * @return Response odpowiedź z formularzem
     */
    #[Route('/admin/profile', name: 'admin_profile', methods: ['GET', 'POST'])]
    public function editProfile(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);
            $this->addFlash('success', 'Dane zostały zaktualizowane.');

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Zmiana hasła administratora.
     *
     * @param Request $request obiekt żądania
     *
     * @return Response odpowiedź z formularzem zmiany hasła
     */
    #[Route('/admin/change-password', name: 'admin_change_password', methods: ['GET', 'POST'])]
    public function changePassword(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $newPassword */
            $newPassword = (string) $form->get('newPassword')->getData();

            $this->userService->changePassword($user, $newPassword, $this->hasher);

            $this->addFlash('success', 'Hasło zostało zmienione.');

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
