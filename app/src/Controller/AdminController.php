<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/profile', name: 'admin_profile')]
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

    #[Route('/change-password', name: 'admin_change_password')]
    public function changePassword(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // pobieramy dane z poszczególnych pól
            $currentPassword = $form->get('currentPassword')->getData();
            $newPassword = $form->get('newPassword')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();

            if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
                $this->addFlash('danger', 'Aktualne hasło jest nieprawidłowe.');
            } elseif ($newPassword !== $confirmPassword) {
                $this->addFlash('danger', 'Hasła muszą być identyczne.');
            } else {
                $user->setPassword(
                    $passwordHasher->hashPassword($user, $newPassword)
                );
                $em->flush();

                $this->addFlash('success', 'Hasło zostało zmienione.');
                return $this->redirectToRoute('app_admin');
            }
        }

        return $this->render('admin/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
