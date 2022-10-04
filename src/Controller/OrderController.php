<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
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
}


