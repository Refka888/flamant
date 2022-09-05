<?php

namespace App\DataFixtures;


use App\Entity\User;
use App\Entity\Order;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;



class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $listOrder = [];
        for ($i=0; $i < 20; $i++){
            $order = new Order();
            $order ->setCode("123456". $i);
            $order ->setStatut("Statut". $i);
            $manager ->persist($order);
            $listOrder[] = $order;
    
         }

     for ($i=0; $i < 20; $i++){
        $user = new User();
        $user ->setfirstName("firstName". $i);
        $user ->setlastName("lastName". $i);
        $user ->setemail("user". $i ."@gmail.com");
        $user ->setpassword("**********". $i);
        $user ->setOrders($listOrder[array_rand($listOrder)]);
        $manager ->persist($user);

     }
        $manager->flush();
    }
}