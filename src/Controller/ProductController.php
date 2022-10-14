<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Product;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/api/products', name: 'app_product' , methods: ['GET'])]
    public function getProductList(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse

    {
            $productList = $productRepository->findAll();
            $jsonProductList = $serializer->serialize($productList, 'json', ['groups' => 'getProducts']);
            return new JsonResponse($jsonProductList, Response::HTTP_OK, [], true);
    }
    #[Route('/api/products/{id}', name: 'detailProduct', methods: ['GET'])]
    public function getDetailProduct(Product $order, SerializerInterface $serializer): JsonResponse 
    {
        $jsonProduct = $serializer->serialize($order, 'json', ['groups' => 'getProducts']);
        return new JsonResponse($jsonProduct, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/products/{id}', name: 'deleteProduct', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour supprimer')]
    public function deleteProduct(Product $product, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($product);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
    #[Route('/api/products', name:"createProduct", methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour créer un produit')]
    public function createUser(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse 
    {

        $product = $serializer->deserialize($request->getContent(), User::class, 'json');
        $em->persist($product);
        $em->flush();

        $jsonProduct = $serializer->serialize($product, 'json', ['groups' => 'getProducts']);
        
        $location = $urlGenerator->generate('detailProduct', ['id' => $product->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonProduct, Response::HTTP_CREATED, ["Location" => $location], true);
   }
   #[Route('/api/products/{id}', name:"updateProduct", methods:['PUT'])]
   #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour mettre à jour le produit')]

   public function updateProduct(Request $request, SerializerInterface $serializer, Product $currentProduct, EntityManagerInterface $em, OrderRepository $orderRepository): JsonResponse 
   {
       $updatedProduct = $serializer->deserialize($request->getContent(), 
               Product::class, 
               'json', 
               [AbstractNormalizer::OBJECT_TO_POPULATE => $currentProduct]);       
       $em->persist($updatedProduct);
       $em->flush();
       return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
  }
}
