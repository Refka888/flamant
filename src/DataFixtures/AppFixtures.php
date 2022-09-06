<?php

namespace App\DataFixtures;

use App\Entity\Cat;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;



class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    { 
        $listUser = [];
     for ($i=0; $i < 20; $i++){
        $user = new User();
        $user ->setfirstName("firstName". $i);
        $user ->setlastName("lastName". $i);
        $user ->setemail("user". $i ."@gmail.com");
        $user ->setpassword("**********". $i);
        $manager ->persist($user);
        $listUser[] = $user;
     }
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
         $order ->setUsers($listUser[array_rand($listUser)]);
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
         $product ->setOrders($listOrder[array_rand($listOrder)]);
         $product ->setCat($listCat[array_rand($listCat)]);
         $manager ->persist($product);
       
      }
        $manager->flush();
    }
}