<?php

declare(strict_types=1);

/**
 * Kontroler odpowiedzialny za logowanie i wylogowanie użytkowników.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Kontroler obsługujący logowanie i wylogowanie użytkowników.
 */
final class SecurityController extends AbstractController
{
    /**
     * Formularz logowania użytkownika.
     *
     * @param AuthenticationUtils $authenticationUtils narzędzie do obsługi procesu logowania
     *
     * @return Response
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser() instanceof \Symfony\Component\Security\Core\User\UserInterface) {
            return $this->redirectToRoute('app_home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Wylogowanie użytkownika (obsługiwane automatycznie przez Symfony).
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): never
    {
        throw new \LogicException('This method is intercepted by Symfony logout mechanism.');
    }
}
