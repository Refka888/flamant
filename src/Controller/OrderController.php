<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Order;
use App\Repository\UserRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/api/orders', name: 'app_order' , methods: ['GET'])]
    public function getOrderList(OrderRepository $orderRepository, SerializerInterface $serializer): JsonResponse

    {
            $orderList = $orderRepository->findAll();
            $jsonOrderList = $serializer->serialize($orderList, 'json', ['groups' => 'getOrders']);
            return new JsonResponse($jsonOrderList, Response::HTTP_OK, [], true);
    }
    #[Route('/api/orders/{id}', name: 'detailOrder', methods: ['GET'])]
    public function getDetailOrder(Order $order, SerializerInterface $serializer): JsonResponse 
    {
        $jsonOrder = $serializer->serialize($order, 'json', ['groups' => 'getOrders']);
        return new JsonResponse($jsonOrder, Response::HTTP_OK, ['accept' => 'json'], true);
    }
   
    #[Route('/api/orders/{id}', name: 'deleteOrder', methods: ['DELETE'])]
    public function deleteOrder(Order $order, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($order);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
    #[Route('/api/orders', name:"createOrder", methods: ['POST'])]
    public function createOrder(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse 
    {

        $order = $serializer->deserialize($request->getContent(), User::class, 'json');
        $em->persist($order);
        $em->flush();

        $jsonOrder = $serializer->serialize($order, 'json', ['groups' => 'geOrders']);
        
        $location = $urlGenerator->generate('detailOrder', ['id' => $order->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonOrder, Response::HTTP_CREATED, ["Location" => $location], true);
   }
   #[Route('/api/orders/{id}', name:"updateOrder", methods:['PUT'])]

   public function updateOrder(Request $request, SerializerInterface $serializer, Order $currentOrder, EntityManagerInterface $em, UserRepository $userRepository): JsonResponse 
   {
       $updatedOrder = $serializer->deserialize($request->getContent(), 
               Order::class, 
               'json', 
        
               [AbstractNormalizer::OBJECT_TO_POPULATE => $currentOrder]);
       $content = $request->toArray();
        $idUser = $content['user'] ;
    $updatedOrder->setUser($userRepository->find($idUser));
       
       $em->persist($updatedOrder);
       $em->flush();
       return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
  }
}


