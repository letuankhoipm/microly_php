<?php

namespace App\Controller;

use App\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

class AuthController extends AbstractController
{
    /**
     * @Route("/register", name="account_register", methods={"POST"})
     */

    public function register(Request $request, PasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $username = $data['username'];
        $plainPassword = $data['password'];
        $roles = $data['roles'] ?? [];

        $existingAccount = $this->entityManager()->getRepository(Account::class)->findOneBy(['username' => $username]);

        if ($existingAccount) {
            return $this->json(['message' => 'Username already exists'], Response::HTTP_CONFLICT);
        }

        $account = new Account();
        $account->setUsername($username);

        $encodedPassword = $passwordEncoder->encodePassword($account, $plainPassword);
        $account->setPassword($encodedPassword);
        $account->setRoles($roles);

        $entityManager = $this->entityManager()->getManager();
        $entityManager->persist($account);
        $entityManager->flush();

        return $this->json(['message' => 'Account registered successfully'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/login", name="account_login", methods={"POST"})
     */
    public function login(): Response
    {
        // This method can be implemented based on your authentication configuration.
        // You can use a Symfony security component or a JWT-based authentication approach.
        // If using Symfony security component, the authentication process is handled automatically.

        return $this->json(['message' => 'Login successful']);
    }
}
