<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecepeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController
{
    #[Route('/recette', name: 'recette.index', methods: ['GET', 'POST'])]
    public function index(PaginatorInterface $paginator, RecipeRepository $repository, Request $request): Response
    {
        $recipes = $paginator->paginate(
            $repository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route("/recette/create", name: "recette.new", methods: ['POST', 'GET'])]
    public function new(ManagerRegistry $doctrine, Request $request): Response
    {

        $manager = $doctrine->getManager();
        $recipe = new Recipe();

        $form = $this->createForm(RecepeType::class, $recipe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();

            $manager->persist($recipe);

            $manager->flush();
            $this->addFlash('succes', message: 'votre recette a été  crée avec succès');
            return $this->redirectToRoute('recette.new');
        }

        return $this->render('pages/recipe/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/recette/update/{id}', name: 'recette.update', methods: ['GET', 'POST'])]
        public function update(Recipe $recipe, ManagerRegistry $doctrine, Request $request): Response
    {
     
        $manager = $doctrine->getManager();
        
        $form = $this->createForm(RecepeType::class, $recipe);

     

            $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $recipe=$form->getData();
        
        $manager->persist($recipe);

        $this->addFlash('succes', message:'votre ingredient a été  modifié avec succès');
        $manager->flush();

        return $this->redirectToRoute('recette.index');
    }else{
        
    }
        $this->addFlash('warning', message: 'recette innexistante');

        return $this->render('pages/recipe/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/recette/delete/{id}', name: 'recette.delete')]
    public function delete(Recipe $recipe, EntityManagerInterface $manager): Response
    {


        if (!$recipe) {
            $this->addFlash('waning', message: 'la recette n\'existe pas');
        } else {

            $manager->remove($recipe);
            $manager->flush();
            $this->addFlash('succes', message: 'votre recette vient d\'etre supprimé avec succès');
        }

        return $this->redirectToRoute('recette.index');
    }
}
