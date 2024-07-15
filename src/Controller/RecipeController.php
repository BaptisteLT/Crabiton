<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Entity\RecipeImage;
use Psr\Log\LoggerInterface;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RecipeController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(RecipeRepository $recipeRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $recipeRepository->findAllWithRating(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );

        return $this->render('recipe/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/recipe/new', name: 'app_recipe_new')]
    public function new(Request $request, ParameterBagInterface $params, EntityManagerInterface $em, LoggerInterface $logger): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            /** @var UploadedFile $brochureFile */
            $recipeImage = $form->get('recipeImage')->getData();

            foreach($recipeImage as $image)
            {
                $newFilename = uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move($params->get('recipe_images_directory'), $newFilename);
                } catch (FileException $e) {
                    $logger->error('Recipe image upload error: '.$e->getMessage());

                    $this->addFlash('error', 'An error occured during the upload of your image(s)');

                    return $this->render('recipe/new.html.twig', [
                        'form' => $form,
                    ]);
                }
    
                $recipeImage = new RecipeImage();
                $recipe->setUser($this->getUser());
                $recipeImage->setImageFilename($newFilename);
                $recipe->addRecipeImage($recipeImage);

                $em->persist($recipeImage);
                $em->persist($recipe);
            }
            $em->flush();
            
            return $this->redirectToRoute('app_index');
        }

        return $this->render('recipe/new.html.twig', [
            'form' => $form,
        ]);
    }
}
