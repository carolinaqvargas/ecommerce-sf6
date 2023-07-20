<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/admin/produits', name: 'admin_products_')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProductsRepository $productsRepository): Response
    {
        $produits = $productsRepository->findAll();
        return $this->render('admin/products/index.html.twig', compact('produits'));
    }

    #[Route('/ajout', name: 'add')]
    public function add(): Response
    {
        //L'ajout est possible uniquement pour les ROLE_ADMIN
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/products/index.html.twig');
    }
    #[Route('/edition/{id}', name: 'edit')]
    public function edit(Products $product): Response
    {
        // On vérifie si l'utilisateur peut éditer avec le Voter
        $this->denyAccessUnlessGranted('PRODUCT_EDIT', $product);

        return $this->render('admin/products/index.html.twig');
    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(Products $product): Response
    {
        return $this->render('admin/products/index.html.twig');
    }
}

 