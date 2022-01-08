<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Stage;
use App\Entity\Entreprise;
use App\Entity\Formation;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //Générateur Faker
        $faker = \Faker\Factory::create('fr_FR');

        $nbEntreprises = $faker->numberBetween($min = 5, $max = 10);
        for($i = 0; $i < $nbEntreprises ; $i++)
        {
            $entreprise = new Entreprise();
            $entreprise->setActivite($faker->sentence($nbWords=6,$variableNbWords=true));
            $entreprise->setAdresse($faker->address);
            $entreprise->nom($faker->company);
            $entreprise->setURLSite($faker->domainName);
        }

        $product = new Product();

        $manager->persist($product);

        $manager->flush();
    }
}
