<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Stage;
use App\Entity\Entreprise;
use App\Entity\Formation;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $utilisateur = new User();
        $utilisateur->setUsername("utilisateur");
        $utilisateur->setPassword("$2y$10\$xnrH19kyib7b3MEaEDF6l.iiUtrAkKkFkDwRqAVkyjaphUL.UdzHO");
        $utilisateur->setRoles(['ROLE_USER']);

        $administrateur = new User();
        $administrateur->setUsername("administrateur");
        $administrateur->setPassword("$2y$10\$s2/CnSZpzIPwCVnVnvFJ9.AWCc.sD3a5.mcEO3TJQQdbYVHbqiAJK");
        $administrateur->setRoles(['ROLE_ADMIN']);

        $manager->persist($utilisateur);
        $manager->persist($administrateur);

        //Générateur Faker
        $faker = \Faker\Factory::create('fr_FR');

        $dutInfo = new Formation();
        $dutInfo->setNomLong("Diplôme Universitaire Technologique Informatique");
        $dutInfo->setNomCourt("DUT Info");

        $LPInfo = new Formation();
        $LPInfo->setNomLong("Licence Professionnelle Multimédia");
        $LPInfo->setNomCourt("LP Mult.");
        

        $nbEntreprises = $faker->numberBetween($min = 15, $max = 40);
        for($i = 0; $i < $nbEntreprises ; $i++)
        {
            $entreprise = new Entreprise();
            $entreprise->setActivite($faker->realText($maxNbChars = 40, $indexSize = 2));
            $entreprise->setAdresse($faker->address);
            $entreprise->setNom($faker->company);
            $entreprise->setURLSite($faker->domainName);
            
            //Génération des stages de chaque entreprise
            $nbStages = $faker->numberBetween($min = 0, $max = 9);
            for($j = 0; $j < $nbStages ; $j++)
            {
                $stage = new Stage;
                $stage->setTitre($faker->realText($maxNbChars = 40, $indexSize = 2));
                $stage->setDescMission($faker->realText($maxNbChars = 1200, $indexSize = 2));
                $stage->setEntreprise($entreprise);
                $stage->setEmailContact($faker->email);
                
                //Formations
                $formationDuStage = $faker->numberBetween($min = 1, $max = 3);
                switch ($formationDuStage) {
                    case '1':
                        $stage->addFormation($LPInfo);
                        $LPInfo->addStage($stage);
                        break;
                    case '2':
                        $stage->addFormation($dutInfo);
                        $dutInfo->addStage($stage);
                        break;
                    default:
                        $stage->addFormation($LPInfo);
                        $stage->addFormation($dutInfo);
                        $LPInfo->addStage($stage);
                        break;
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
