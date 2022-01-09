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

        $LPInfo = new Formation();
        $LPInfo->setNomLong("Licence Professionnelle Multimédia");
        $LPInfo->setNomCourt("LP Mult.");
        

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
            for($j = 0; $j < $nbStages ; $j++)
            {
                $stage = new Stage;
                $stage->setTitre($faker->realText($maxNbChars = 40, $indexSize = 2));
                $stage->setDescMission($faker->realText($maxNbChars = 1200, $indexSize = 2));
                $stage->setEntreprise($entreprise);
                $stage->setEmailContact($faker->email);
                
                //Formations
                $formationDuStage = $faker->numberBetween($min = 1, $max = 3);
                
                if(($formationDuStage-2)>= 0)
                {
                    $stage->addFormation($LPInfo);
                    $LPInfo->addStage($stage);
                    $formationDuStage-2;
                }
                if(($formationDuStage-1)>= 0)
                {
                    $stage->addFormation($dutInfo);
                    $dutInfo->addStage($stage);
                    $formationDuStage-1;
                }
                $entreprise->addStage($stage);
                $manager->persist($stage);
            }
            $manager->persist($entreprise);
        }



        $manager->persist($dutInfo);
        $manager->persist($LPInfo);
        $manager->flush();
    }
}
