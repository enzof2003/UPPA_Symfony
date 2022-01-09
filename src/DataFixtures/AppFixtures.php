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

        $dutInfo = new Formation();
        $dutInfo->setNomLong("Diplôme Universitaire Technologique Informatique");
        $dutInfo->setNomCourt("DUT Info");
        

        $nbEntreprises = $faker->numberBetween($min = 5, $max = 10);
        for($i = 0; $i < $nbEntreprises ; $i++)
        {
            $entreprise = new Entreprise();
            $entreprise->setActivite($faker->realText($maxNbChars = 40, $indexSize = 2));
            $entreprise->setAdresse($faker->address);
            $entreprise->setNom($faker->company);
            $entreprise->setURLSite($faker->domainName);
            
            //Génération des stages de chaque entreprise
            $nbStages = $faker->numberBetween($min = 0, $max = 4);
            for($i = 0; $i < $nbEntreprises ; $i++)
            {
                $stage = new Entreprise;
                $stage->setTitre($faker->realText($maxNbChars = 40, $indexSize = 2));
                $stage->setDescMission($faker->realText($maxNbChars = 1200, $indexSize = 2));
                $stage->setDescMission($faker->email);
                $stage->setEntreprise($entreprise);
                
                //Formations
                $formationDuStage = $faker->numberBetween($min = 1, $max = 3);
                
                
                $entreprise->setStages($stage);
            }
            $manager->persist($entreprise);
        }



        $manager->persist($dutInfo);
        $manager->persist($dutInfo);
        $manager->flush();
    }
}
