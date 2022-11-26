<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'ingredient.alls')]
    public function getAllIngredient(IngredientRepository $repository,PaginatorInterface $paginator,Request $request): Response
    {
        $ingredients = $paginator->paginate(
            $repository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients'=>$ingredients
        ]);
    }
    #[Route('/ingredient/new', name: 'ingredient.new',methods:['GET','POST'])]
    public function new(Request $request,ManagerRegistry $doctrine):Response
    {
        $manager=$doctrine->getManager();
        
        $ingredient= new Ingredient();

        $form=$this->createForm(IngredientType::class,$ingredient);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $ingredient=$form->getData();
            $manager->persist($ingredient);

            $this->addFlash('succes', message:'votre ingredient a été  crée avec succès');
            $manager->flush();

            return $this->redirectToRoute('ingredient.alls');
        }
        return $this->render("pages/ingredient/add.html.twig",[
            'form'=>$form->createView()
        ]);
    }
    #[Route("/ingredient/edit/{id}", name:"ingredient.edit", methods:['POST','GET'])]
    public function edit(Ingredient $ingredient,ManagerRegistry $doctrine,Request $request):Response
    {
        $manager=$doctrine->getManager();
        $form=$this->createForm(IngredientType::class,$ingredient);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $ingredient=$form->getData();
            $manager->persist($ingredient);

            $this->addFlash('succes', message:'votre ingredient a été  modifié avec succès');
            $manager->flush();

            return $this->redirectToRoute('ingredient.alls');
        }

        

        return $this->render('pages/ingredient/edit.html.twig',[
            'form'=>$form->createView()

        ]);
    }
    #[Route('/ingredient/delete/{id}', name:'ingredient.delete', methods:['GET'])]
    public function delete(EntityManagerInterface $manager,Ingredient $ingredient):Response
    {

        if(!$ingredient){

            $this->addFlash('warning', message:"l'ingrédient n'existe pas");
        }else{

            $manager->remove($ingredient);
            $manager->flush();
            $this->addFlash('succes', message:'votre ingredient a été  supprimé avec succès');

        }

        return $this->redirectToRoute('ingredient.alls');
    }
}
