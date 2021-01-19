<?php

namespace App\DataFixtures;

use App\Entity\RealEstate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
       $realEstate = new RealEstate();
       $realEstate->setTitle('Un appartement FIXTURES');
       $realEstate->setDescription('Une description');
       $realEstate->setSurface('10');
       $realEstate->setPrice(100000);
        $realEstate->setRooms(4);
       $realEstate->setType('appartement');
       $realEstate->setSold(false);

       $manager->persist($realEstate);
       $manager->flush();
    }
}
