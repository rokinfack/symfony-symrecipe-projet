<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/utilisateur/edition/{id}', name: 'user.edit', methods: ['GET', 'POST'])]
    public function edit(User $user, ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $manager = $doctrine->getManager();

        if (!$this->getUser()) {

            return $this->redirectToRoute('security.login');
        }
        if ($this->getUser() !== $user) {

            return $this->redirectToRoute('ingredient.alls');
        }
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->getData()->getPassword();
            if ($hasher->isPasswordValid($user, $password)) {
                $user = $form->getData();
                $user = $form->getData();
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('success', message: 'Les informations de votre compte ont bien été modifiées!');
                return $this->redirectToRoute('recette.index');
            } else {

                $this->addFlash('warning', message: "Le mot de passe renseigné est incorrect!");
            }
        }

        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/users', name: 'users.index')]
    public function index(UserRepository $userRepository): Response
    {

        $users = $userRepository->findAll();

        return $this->render(
            'pages/user/index.html.twig',
            [
                'users' => $users,
            ]
        );
    }
    #[Route('/utilisateur/edition-mot-de-passe/{id}','user.edit.password')]
    public function editPassword(User $user, Request $request,UserPasswordHasherInterface $hasher,EntityManagerInterface $manager):Response
    {
        $form=$this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $password=$form->getData()['password'];
            if($hasher->isPasswordValid($user,$password))
            {
                $user->setPassword($hasher->hashPassword($user,$form->getData()['newPassword']));

            }
            $this->addFlash('success', message: 'votre mot de passe à été modifié avec succès!');
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('recette.index');
        } else {

            $this->addFlash('warning', message: "Le mot de passe renseigné est incorrect!");
        }
        
        return $this->render('pages/user/edit_password.html.twig',
    [
        'form'=>$form->createView(),
    ]);

    }
}
