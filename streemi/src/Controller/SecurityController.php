<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Uid\Uuid;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastEmail= $authenticationUtils->getLastUsername();

        return $this->render('auth/login.html.twig', [
            'last_username' => $lastEmail,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/register', name: 'page_register')]
    public function register(): Response
    {
        return $this->render('auth/register.html.twig');
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route(path: '/forgot', name: 'page_forgot_password')]
    public function forgotPassword(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->get('email');
            $user = $userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                $this->addFlash('error', 'Aucun utilisateur trouvé avec cet email.');
            } else {
                $resetPasswordToken = Uuid::v4()->toRfc4122();
                $user->setResetPasswordToken($resetPasswordToken);
                $entityManager->flush();

                // Envoi de l'email de réinitialisation
                $resetUrl = $this->generateUrl('reset_password', ['token' => $resetPasswordToken], UrlGeneratorInterface::ABSOLUTE_URL);

                $emailMessage = (new TemplatedEmail())
                    ->from('no-reply@streemi.com')
                    ->to($user->getEmail())
                    ->subject('Réinitialisation de votre mot de passe')
                    ->htmlTemplate('email/reset.html.twig')
                    ->context([
                        'resetUrl' => $resetUrl,
                        'user_email' => $user->getEmail(),
                    ]);


                $mailer->send($emailMessage);
                dump('Email envoyé'); // Test temporaire

                $this->addFlash('success', 'Un email de réinitialisation a été envoyé.');
            }
        }

        return $this->render('auth/forgot.html.twig');
    }


    #[Route(path: '/reset/{token}', name: 'reset_password')]

    public function resetPassword(string $token, UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Trouver l'utilisateur par son token de réinitialisation
        $user = $userRepository->findOneBy(['resetPasswordToken' => $token]);

        if (!$user) {
            // Si l'utilisateur n'est pas trouvé, retourner une erreur
            $this->addFlash('error', 'Token invalide ou expiré.');
            return $this->redirectToRoute('page_forgot_password');
        }

        if ($request->isMethod('POST')) {
            // Récupérer les mots de passe du formulaire
            $newPassword = $request->get('password');
            $repeatPassword = $request->get('repeat-password');

            // Vérifier si les mots de passe correspondent
            if ($newPassword !== $repeatPassword) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('reset_password', ['token' => $token]);
            }

            // Contrôles sur le nouveau mot de passe
            $passwordRegex = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/';
            if (!preg_match($passwordRegex, $newPassword)) {
                $this->addFlash('error', 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre.');
                return $this->redirectToRoute('reset_password', ['token' => $token]);
            }

            // Hasher le nouveau mot de passe
            $user->setPassword(password_hash($newPassword, PASSWORD_BCRYPT));
            $user->setResetPasswordToken(null); // Supprimer le token de réinitialisation

            // Sauvegarder l'utilisateur dans la base de données
            $entityManager->flush();

            // Afficher un message de succès
            $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès.');

            // Rediriger l'utilisateur vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        // Afficher le formulaire de réinitialisation
        return $this->render('auth/reset.html.twig', ['token' => $token]);
    }


    #[Route(path: '/confirm', name: 'page_confirm_account')]
    public function confirmAccount(): Response
    {
        return $this->render('auth/confirm.html.twig');
    }
}

