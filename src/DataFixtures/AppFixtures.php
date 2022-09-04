<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
     for ($i=0; $i < 20; $i++){
        $user = new Users();
        $user ->setfirstName("firstName". $i);
        $user ->setlastName("lastName". $i);
        $user ->setemail("user". $i ."@gmail.com");
        $user ->setPassword("**********". $i);
        $manager ->persist($user);

     }
        $manager->flush();
    }
}
