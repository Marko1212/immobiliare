<?php

namespace App\DataFixtures;

use App\Entity\RealEstate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr:FR');
        for($i=1; $i <=100; $i++) {
            $realEstate = new RealEstate();
            $type = $faker->randomElement(['maison', 'appartement']);
            $title = ucfirst($type).' ';
            $rooms = $faker->numberBetween(1, 5);
            //dump($rooms);
            $title .= RealEstate::SIZES[$rooms]; // T2, T3, T4
            // Appartement (avec jardin, avec balcon)
            // Maison T4 (en centre-ville ou en campagne)
            $realEstate->setTitle($title);
           // $realEstate->setSlug($faker->slug);
            $slug = $this->slugger->slug($realEstate->getTitle())->lower();
            $realEstate->setSlug($slug);
            
            $realEstate->setDescription($faker->text(2000));
            $realEstate->setSurface($faker->numberBetween(10, 400));
            $realEstate->setPrice($faker->numberBetween(34875, 584725));
            $realEstate->setRooms($rooms);
            $realEstate->setType($type);
            $realEstate->setSold($faker->boolean(10)); // 10% de chances d'avoir true
            $manager->persist($realEstate);

        }

        $manager->flush();

    }
}
