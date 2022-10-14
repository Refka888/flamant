<?php

namespace App\DataFixtures;

use App\Entity\Cat;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasher;
    
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public function load(ObjectManager $manager): void
    { 
        $listUser = [];
     for ($i=0; $i < 20; $i++){
        $user = new User();
        $user ->setfirstName("firstName". $i);
        $user ->setlastName("lastName". $i);
        $user->setEmail("user".$i."@shop.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
        $manager ->persist($user);
        $listUser[] = $user;
     }
       // Création d'un user admin
        $userAdmin = new User();
        $userAdmin ->setfirstName("firstName". $i);
        $userAdmin ->setlastName("lastName". $i);
        $userAdmin->setEmail("admin@shop.com");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "password"));
        $manager->persist($userAdmin);
     
     
     $listCat = [];
     for ($i=0; $i < 10; $i++){
         $cat = new Cat();
         $cat ->setName("Name". $i);
         $manager ->persist($cat);
         $listCat[] = $cat;
     }
        $listOrder = [];
     for ($i=0; $i < 20; $i++){
         $order = new Order();
         $order ->setCode("123456". $i);
         $order ->setStatut("Statut". $i);
         $order ->setUser($listUser[array_rand($listUser)]);
         $manager ->persist($order);
         $listOrder[] = $order;
     }
    
      $listQuantity = [10, 20, 100, 150, 250, 300, 320, 350];
      for ($i=0; $i < 20; $i++){
         $product = new Product();
         $product ->setName("Name". $i);
         $product ->setDescription("description". $i);
         $product ->setImage("image". $i);
         $product ->setQuantity($listQuantity[array_rand($listQuantity)]);
         $product ->setPrice($listQuantity[array_rand($listQuantity)]);
        // $product ->setOrders($listOrder[array_rand($listOrder)]);
         $product ->setCategory($listCat[array_rand($listCat)]);
         $manager ->persist($product);
       
      }
        $manager->flush();
    }
}