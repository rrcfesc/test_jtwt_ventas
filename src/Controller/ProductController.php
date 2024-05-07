<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        $response = [];
        /** @var Product $product */
        foreach ($productRepository->findAll() as $product) {
            $response[] = [
                'id' => $product->getId(),
                'name' => $product->getName()
            ];
        }

        return new JsonResponse($response);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            return new JsonResponse(['id' => $product->getId()], Response::HTTP_CREATED);
        }

        return new JsonResponse(['message' => 'only accessible over post'], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return new JsonResponse([
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price'
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return new JsonResponse(['id' => $product->getId()], Response::HTTP_ACCEPTED);
        }

        return new JsonResponse(['message' => 'only accessible over post'], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['DELETE'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
//        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
//        }

        return new JsonResponse([], Response::HTTP_ACCEPTED);
    }
}
