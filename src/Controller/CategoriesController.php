<?php

namespace App\Controller;

use App\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'app_categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/{slug}', name:'list')]
    public function list(Categories $category): Response
    {
        //dd($category);

        //On va chercher la liste de produits de la categorie
        $products = $category->getProducts();

        return $this->render('categories/list.html.twig', compact('category', 'products'));
    }
}

