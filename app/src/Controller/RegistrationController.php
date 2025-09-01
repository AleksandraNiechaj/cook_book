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
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Controller responsible for user registration.
 */
final class RegistrationController extends AbstractController
{
    /**
     * Register a new user.
     *
     * @param Request                     $request    The current HTTP request
     * @param EntityManagerInterface      $em         The entity manager
     * @param UserPasswordHasherInterface $hasher     The password hasher
     * @param TranslatorInterface         $translator The translator
     *
     * @return Response
     */
    #[Route(path: '/register', name: 'auth_register', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher, TranslatorInterface $translator): Response
    {
        if ($this->getUser() instanceof \Symfony\Component\Security\Core\User\UserInterface) {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(RegistrationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $email */
            $email = (string) $form->get('email')->getData();
            /** @var string $plainPassword */
            $plainPassword = (string) $form->get('plainPassword')->getData();

            $user = new User();
            $user->setEmail($email);
            $user->setPassword($hasher->hashPassword($user, $plainPassword));
            $user->setRoles(['ROLE_USER']);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', $translator->trans('flash.registration_success'));

            return $this->redirectToRoute('app_login');
        }

        return $this->render('auth/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
