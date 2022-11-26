<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'security.login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('pages/security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'=>$authenticationUtils->getLastAuthenticationError()
        ]);
    }
    #[Route('/deconnexion', name:'security.logout') ]
    public function logout()
    {
    }
    #[Route('/inscription', name:'security.registration',methods:['POST','GET'])]
    public function registration( ManagerRegistry $doctrine,Request $request):Response{

        $manager=$doctrine->getManager();
        $user=new User();
        $form=$this->createForm(RegistrationType::class,$user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $user=$form->getData();

            $this->addFlash('succes',message:'Votre compte a bien été crée');

            $manager->persist($user);

            $manager->flush();
            
            return $this->redirectToRoute('ingredient.alls');
        }

        return $this->render('pages/security/registration.html.twig',
    [
        'form'=>$form->createView()
    ]);


    }
}
