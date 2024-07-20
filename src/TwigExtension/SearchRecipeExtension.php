<?php
namespace App\TwigExtension;

use Twig\TwigFunction;
use App\Form\SearchRecipeType;
use Twig\Extension\AbstractExtension;
use Symfony\Component\Form\FormFactoryInterface;

class SearchRecipeExtension extends AbstractExtension
{
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('search_form', [$this, 'getSearchForm']),
        ];
    }

    public function getSearchForm()
    {
        $form = $this->formFactory->create(SearchRecipeType::class);

        return $form->createView();
    }
}
