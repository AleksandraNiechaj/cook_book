<?php

declare(strict_types=1);

/**
 * This file is part of the Cook Book project.
 * (c) 2025 Aleksandra Niechaj
 * License: For educational purposes (course project).
 */

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

/**
 * Obsługa logowania przez formularz.
 */
class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    /**
     * Konstruktor.
     *
     * @param UrlGeneratorInterface $urlGenerator Generator URL.
     */
    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    /**
     * Tworzy paszport użytkownika na podstawie danych z formularza.
     *
     * @param Request $request Obiekt żądania HTTP.
     *
     * @return Passport Paszport logowania.
     */
    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $request->getSession()->set('_last_username', $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [new RememberMeBadge()]
        );
    }

    /**
     * Akcja po poprawnym zalogowaniu.
     *
     * @param Request        $request      Żądanie HTTP.
     * @param TokenInterface $token        Token uwierzytelnienia.
     * @param string         $firewallName Nazwa firewalla.
     *
     * @return RedirectResponse|null Przekierowanie po logowaniu.
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?RedirectResponse
    {
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    /**
     * Zwraca URL do strony logowania.
     *
     * @param Request $request Żądanie HTTP.
     *
     * @return string Ścieżka do logowania.
     */
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('app_login');
    }
}
