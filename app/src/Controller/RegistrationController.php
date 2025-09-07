<?php

declare(strict_types=1);

/**
 * Kontroler rejestracji nowych użytkowników.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Kontroler obsługujący rejestrację nowych użytkowników.
 */
final class RegistrationController extends AbstractController
{
    /**
     * Konstruktor.
     *
     * @param UserService                 $userService serwis użytkowników
     * @param UserPasswordHasherInterface $hasher      hasher haseł
     */
    public function __construct(private readonly UserService $userService, private readonly UserPasswordHasherInterface $hasher)
    {
    }

    /**
     * Formularz rejestracji użytkownika.
     *
     * @param Request             $request    obiekt żądania
     * @param TranslatorInterface $translator tłumacz komunikatów
     *
     * @return Response
     */
    #[Route(path: '/register', name: 'auth_register', methods: ['GET', 'POST'])]
    public function register(Request $request, TranslatorInterface $translator): Response
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
            $user->setPassword($this->hasher->hashPassword($user, $plainPassword));
            $user->setRoles(['ROLE_USER']);

            $this->userService->save($user);

            $this->addFlash('success', $translator->trans('flash.registration_success'));

            return $this->redirectToRoute('app_login');
        }

        return $this->render('auth/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
