<?php

namespace App\Controller\Admin;

use App\Entity\Images;
use App\Entity\Products;
use App\Form\ProductsFormType;
use App\Repository\ProductsRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        //L'ajout est possible uniquement pour les ROLE_ADMIN
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //On crée un "nouveau produit"
        $product = new Products();

        // On crée le formulaire
        $productForm = $this->createForm(ProductsFormType::class, $product);

        return $this->render('admin/products/add.html.twig', [
            'productForm' => $productForm,
        ]);

        // On traite la requête du formulaire
        $productForm->handleRequest($request);

        //dd($productForm)

        //On vérifie si le formulaire est soumis ET valide
        if ($productForm->isSubmitted() && $productForm->isValid()) {
           
            // On génère le slug
            $slug = $slugger->slug($product->getName());   //dd($lug)
            $product->setSlug($slug);

            //On arrondit le prix 
            $prix = $product->getPrice() * 100;
            $product->setPrice($prix);

            // On stocke
            $em->persist($product);
            $em->flush();

            // $this->addFlash('success', 'Produit ajouté avec succès');

            // On redirige
            return $this->redirectToRoute('admin_products_index');
        }

     
    }

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(Products $product, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        // On vérifie si l'utilisateur peut éditer avec le Voter
        $this->denyAccessUnlessGranted('PRODUCT_EDIT', $product);

        // On crée le formulaire
        $productForm = $this->createForm(ProductsFormType::class, $product);

        return $this->render('admin/products/add.html.twig', [
            'productForm' => $productForm,
        ]);

        // On traite la requête du formulaire
        $productForm->handleRequest($request);

        //dd($productForm)

        //On vérifie si le formulaire est soumis ET valide
        if ($productForm->isSubmitted() && $productForm->isValid()) {

            // On génère le slug
            $slug = $slugger->slug($product->getName());   //dd($lug)
            $product->setSlug($slug);

            //On arrondit le prix 
            $prix = $product->getPrice() * 100;
            $product->setPrice($prix);

            // On stocke
            $em->persist($product);
            $em->flush();

            // $this->addFlash('success', 'Produit ajouté avec succès');

            // On redirige
            return $this->redirectToRoute('admin_products_index');



        return $this->render('admin/products/edit.html.twig');
    }
}

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(Products $product): Response
    {
        return $this->render('admin/products/suppression.html.twig');
    }
}
