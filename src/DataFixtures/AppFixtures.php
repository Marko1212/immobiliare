<?php

namespace App\DataFixtures;

use App\Entity\RealEstate;
use App\Entity\Type;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private $slugger;
    private $passwordEncoder;

    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->slugger = $slugger;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr:FR');

        // On crée un User pour pouvoir se connecter

        $user = new User();
        $user->setEmail('askmarko@hotmail.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'test'));
        $user->setRoles(['ROLE_ADMIN']);
        $this->addReference('user-0', $user);
        $manager->persist($user);

        //Création d'utilisateurs "lambda"
        
        for ($i=1; $i <= 9; $i++) {
            $user = new User();                                                 
            $user->setEmail($faker->email);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'test'));
            $this->addReference('user-'.$i, $user);
            $manager->persist($user);
        }
        
        
        // On crée des catégories avant de créer des annonces

        $typeNames = ['Maison', 'Appartement', 'Villa', 'Garage', 'Studio'];
        foreach($typeNames as $key=>$typeName) {
            $type=new Type();
            $type->setName($typeName);
            $this->addReference('type-'. $key, $type);
            $manager->persist($type);
        }
        for($i=1; $i <=100; $i++) {
            $realEstate = new RealEstate();
            $type = $this->getReference('type-' . rand(0, count($typeNames) - 1)); //on prend une catégorie aléatoire
            $title = ucfirst($type->getName()).' ';
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
            $realEstate->setImage($faker->randomElement(['default.png', 'fixtures/1.jpg', 'fixtures/2.jpg', 'fixtures/3.jpg']));

            $realEstate->setOwner($this->getReference('user-' . rand(0, 9))); // user-0 c'est l'admin
            $manager->persist($realEstate);

        }

        $manager->flush();

    }
}
