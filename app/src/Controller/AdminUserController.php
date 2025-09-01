<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminChangePasswordType;
use App\Form\AdminUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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
#[Route('/admin/users')]
final class AdminUserController extends AbstractController
{
    /**
     * Lista użytkowników z paginacją i sortowaniem.
     *
     * @param Request        $request Żądanie HTTP
     * @param UserRepository $users   Repozytorium użytkowników
     *
     * @return Response
     */
    #[Route('', name: 'admin_user_index', methods: ['GET'])]
    public function index(Request $request, UserRepository $users): Response
    {
        $page   = \max(1, (int) $request->query->get('page', 1));
        $limit  = 10;
        $offset = ($page - 1) * $limit;

        $allowedSorts = ['id', 'email'];
        $sort = \in_array((string) $request->query->get('sort', 'email'), $allowedSorts, true)
            ? (string) $request->query->get('sort', 'email')
            : 'email';

        $dir = \strtoupper((string) $request->query->get('dir', 'ASC'));
        $dir = \in_array($dir, ['ASC', 'DESC'], true) ? $dir : 'ASC';

        $qb = $users->createQueryBuilder('u')
            ->orderBy('u.' . $sort, $dir)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $items = $qb->getQuery()->getResult();

        $total = (int) $users->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $pages = (int) \ceil($total / $limit);

        return $this->render('admin/user_index.html.twig', [
            'items' => $items,
            'page'  => $page,
            'pages' => $pages,
            'total' => $total,
            'sort'  => $sort,
            'dir'   => $dir,
            'limit' => $limit,
        ]);
    }

    /**
     * Edycja danych użytkownika (email, role).
     *
     * @param Request                $request Żądanie HTTP
     * @param EntityManagerInterface $em      Menedżer encji
     * @param User                   $user    Użytkownik do edycji (param converter)
     *
     * @return Response
     */
    #[Route('/{id}/edit', name: 'admin_user_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em, User $user): Response
    {
        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
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
     * @param Request                     $request Żądanie HTTP
     * @param EntityManagerInterface      $em      Menedżer encji
     * @param UserPasswordHasherInterface $hasher  Hasher haseł
     * @param User                        $user    Użytkownik
     *
     * @return Response
     */
    #[Route('/{id}/password', name: 'admin_user_password', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function changePassword(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
        User $user
    ): Response {
        $form = $this->createForm(AdminChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $newPassword */
            $newPassword = (string) $form->get('newPassword')->getData();

            $user->setPassword($hasher->hashPassword($user, $newPassword));
            $em->flush();

            $this->addFlash('success', 'Hasło użytkownika zostało zmienione.');

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user_password.html.twig', [
            'form' => $form->createView(),
            'item' => $user,
        ]);
    }
}
