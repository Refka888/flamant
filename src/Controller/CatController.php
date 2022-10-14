<?php

namespace App\Controller;

use App\Entity\Cat;
use App\Entity\User;
use App\Repository\CatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
       // if($cat){

            $jsonCat = $serializer->serialize($cat, 'json', ['groups' => 'getCats']);
            return new JsonResponse($jsonCat, Response::HTTP_OK, ['accept' => 'json'], true);
       
        
        //}
   
    } 
    #[Route('/api/cats/{id}', name: 'deleteCat', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour supprimer une catégorie')]

    public function deleteCat(Cat $cat, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($cat);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
    #[Route('/api/cats', name:"createCat", methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour créer une catégorie')]
    public function createCat(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse 
    {

        $cat = $serializer->deserialize($request->getContent(), Cat::class, 'json');
        $em->persist($cat);
        $em->flush();

        $jsonCat = $serializer->serialize($cat, 'json', ['groups' => 'getCats']);
        
        $location = $urlGenerator->generate('detailCat', ['id' => $cat->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonCat, Response::HTTP_CREATED, ["Location" => $location], true);
   }
}
