<?php

namespace App\DataFixtures;

use App\Entity\Orders;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $listOrder = [];
        for ($i=0; $i < 20; $i++){
            $order = new Orders();
            $order ->setCode("Code". $i);
            $order ->setStatut("Statut". $i);
            $manager ->persist($order);
            $listOrder[] = $order;
    
         }

     for ($i=0; $i < 20; $i++){
        $user = new Users();
        $user ->setfirstName("firstName". $i);
        $user ->setlastName("lastName". $i);
        $user ->setemail("user". $i ."@gmail.com");
        $user ->setPassword("**********". $i);
        $user ->setOrders($listOrder[array_rand($listOrder)]);
        $manager ->persist($user);

     }
        $manager->flush();
    }
}
