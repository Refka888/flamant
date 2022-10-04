<?php

namespace App\Controller;

use App\Entity\Cat;
use App\Repository\CatRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatController extends AbstractController
{
    #[Route('/api/cats', name: 'app_cat' , methods: ['GET'])]
    public function getCatList(CatRepository $catRepository, SerializerInterface $serializer): JsonResponse

    {
            $catList = $catRepository->findAll();
            $jsonCatList = $serializer->serialize($catList, 'json', ['groups' => 'getCats']);
            return new JsonResponse($jsonCatList, Response::HTTP_OK, [], true);
    }
    #[Route('/api/cats/{id}', name: 'detailCat', methods: ['GET'])]
    public function getDetailCat(Cat $cat, SerializerInterface $serializer): JsonResponse 
    {
        $jsonCat = $serializer->serialize($cat, 'json', ['groups' => 'getCats']);
        return new JsonResponse($jsonCat, Response::HTTP_OK, ['accept' => 'json'], true);
    } 
}
