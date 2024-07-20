<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Entity\RecipeImage;
use App\Entity\UserFavoriteRecipe;
use Psr\Log\LoggerInterface;
use App\Repository\RecipeRepository;
use App\Repository\UserFavoriteRecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RecipeController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(RecipeRepository $recipeRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $search = $request->get('search_recipe')['search'] ?? null;

        $pagination = $paginator->paginate(
            $recipeRepository->searchWithRating($this->getUser(), $search), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/ 
            9 /*limit per page*/
        );

        return $this->render('recipe/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/recipe/favorite/{recipe}', name: 'app_favorite_recipe', methods: ['post'])]
    public function favoriteRecipe(Recipe $recipe, UserFavoriteRecipeRepository $userFavoriteRecipeRepository, Request $request, CsrfTokenManagerInterface $csrfTokenManager, EntityManagerInterface $em): JsonResponse
    {
        $jsonResponse = new JsonResponse();
        $csrfToken = new CsrfToken('favoriteRecipe', $request->request->get('csrf'));
        $user = $this->getUser();

        if(!$user){
            return new JsonResponse('User is not logged in.', Response::HTTP_UNAUTHORIZED);
        }
        if(!$csrfTokenManager->isTokenValid($csrfToken)){
            return new JsonResponse('Invalid CSRF token.', Response::HTTP_BAD_REQUEST);
        }

        $userFavoriteRecipe = $userFavoriteRecipeRepository->findOneBy(['user' => $user, 'recipe' => $recipe]);

        if(!$userFavoriteRecipe)
        {
            $isFavorite = true;

            $userFavoriteRecipe = (new UserFavoriteRecipe())
            ->setUser($user)
            ->setRecipe($recipe);

            $em->persist($userFavoriteRecipe);
        }
        else
        {
            $isFavorite = false;

            $em->remove($userFavoriteRecipe);
        }
        $em->flush();

        return new JsonResponse(['isFavorite' => $isFavorite]);
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
