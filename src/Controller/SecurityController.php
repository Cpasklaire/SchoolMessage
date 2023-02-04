<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route('/enregistrer', name: 'registration')]
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $user->setCreatedAt(new \DateTimeImmutable());
            //password
            $plaintextPassword = $user->getPassword();
            $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);
            $user->setPassword($hashedPassword);
            //email
            $slugger = new AsciiSlugger();
            $slug = $slugger->slug($user->getPseudo());
            $email = $slug."@school.com";
            $user->setEmail($email);
            
            $manager->persist($user);
            $manager->flush();

            $this->addFlash("flash", "Inscription réussie !");
            return $this->redirectToRoute('login');
        }
        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout() {}
    #[Route('/connection', name: 'login')]
    public function login() {
        return $this->render('security/login.html.twig');
    }    
    #[Route('/login_check', name: 'login_check')]
    public function login_check() {}    
}