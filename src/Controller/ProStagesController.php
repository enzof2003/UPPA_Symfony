<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Stage;
use App\Entity\Entreprise;
use App\Entity\Formation;
use App\Form\EntrepriseType;
use App\Form\StageType;
use Symfony\Component\HttpFoundation\Request;
use  Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ProStagesController extends AbstractController
{
    /**
     * @Route("/", name="pro_stages-accueil")
     */
    public function index(): Response
    {
        /*$formEntreprise = $this->createFormBuilder($data)
        ->add('value', TextType::class, array('required' => false))
        ->getForm();*/

        $repositoryEntreprise=$this->getDoctrine()->getRepository(Entreprise::class);
        $repositoryFormation = $this->getDoctrine()->getRepository(Formation::class);
        $entreprises = $repositoryEntreprise->findAll();
        $formations = $repositoryFormation->findAll();
        return $this->render('pro_stages/index.html.twig', [
            'formations'=> $formations,
            'entreprises'=> $entreprises,
        ]);
    }


    /**
     * @Route("/tous-les-stages", name="pro_stages-tous-les-stages")
     */
    public function tousLesStages(): Response
    {
        $repositoryStage=$this->getDoctrine()->getRepository(Stage::class);
        $stages=$repositoryStage->retriveStagesEtEntreprises();
        return $this->render('pro_stages/listeStages.html.twig', [
            'stages'=> $stages,
        ]);
    }


	/**
     * @Route("/entreprises/{id}", name="pro_stages-entreprises")
     */
    public function entreprises($id): Response
    {
        $repositoryStage=$this->getDoctrine()->getRepository(Stage::class);
        $repositoryEntreprise=$this->getDoctrine()->getRepository(Entreprise::class);
        $tupleEntreprise = $repositoryEntreprise->find($id);
        $stages=$repositoryStage->retriveStagesEtEntreprisesParEntreprise($tupleEntreprise->getId());
        $titreEntreprise=$tupleEntreprise->getNom();
        return $this->render('pro_stages/entreprises.html.twig', [
            'entrepriseCherche' => $titreEntreprise,
            'stages'=> $stages,
        ]);
        
    }
	
	/**
     * @Route("/formations/{id}", name="pro_stages-formations")
     */
    public function formations($id): Response
    {
        $repositoryFormation = $this->getDoctrine()->getRepository(Formation::class);
        $formation=$repositoryFormation->Find($id);
        $titreFormation=$formation->getNomLong();
        $repositoryStage=$this->getDoctrine()->getRepository(Stage::class);
        $listeStages=$repositoryStage-> retriveStagesEtEntreprisesParFormation($titreFormation);
        
        return $this->render('pro_stages/formations.html.twig', [
            'formation'=>$formation,
            'titreFormation'=>$titreFormation,
            'listeStages'=>$listeStages,]);
    }
	
		/**
     * @Route("/stages/{id}", name="pro_stages-stages")
     */
    public function stages($id): Response
    {
        $repositoryStage=$this->getDoctrine()->getRepository(Stage::class);
        $stageSelectionne= $repositoryStage->find($id);
        $repositoryEntreprise=$this->getDoctrine()->getRepository(Entreprise::class);
        $tupleEntreprise = $repositoryEntreprise->find($stageSelectionne->getEntreprise());
        

		return $this->render('pro_stages/stages.html.twig',[
        'id' => $id,
        'stage'=> $stageSelectionne,
        'entreprise'=> $tupleEntreprise,
        ]);
    }

    /**
     * @Route("/modifier/entreprise/{id}", name="pro_stages-modifier-entreprise")
     * @IsGranted ("ROLE_ADMIN")
     */
    public function modifierEntreprise(Request $request, EntityManagerInterface $manager, Entreprise $entreprise): Response
    {
        //Création du formulaire

        $formulaireEntreprise=$this->createForm(EntrepriseType::class, $entreprise);

        $formulaireEntreprise->handleRequest($request);

        
        if($formulaireEntreprise->isSubmitted() && $formulaireEntreprise->isValid())
        {
            $manager->persist($entreprise);
            $manager->flush();
        }
        
        dump($entreprise);


        //Représentation graphique du form
        $vueFormulaire = $formulaireEntreprise->createView();

        return $this->render('pro_stages/modifierEntreprise.html.twig', ['vueFormulaire' => $vueFormulaire]);
    }

    /**
     * @Route("/ajouter/entreprise", name="pro_stages-ajouter-entreprise")
     * @IsGranted ("ROLE_ADMIN")
     */
    public function ajouterEntreprise(Request $request, EntityManagerInterface $manager): Response
    {
        $entreprise = new Entreprise();

        //Création du formulaire

        $formulaireEntreprise=$this->createForm(EntrepriseType::class, $entreprise);

        $formulaireEntreprise->handleRequest($request);

        
        if($formulaireEntreprise->isSubmitted() && $formulaireEntreprise->isValid())
        {
            $manager->persist($entreprise);
            $manager->flush();
        }
        
        dump($entreprise);


        //Représentation graphique du form
        $vueFormulaire = $formulaireEntreprise->createView();

        return $this->render('pro_stages/ajoutEntreprise.html.twig', ['vueFormulaire' => $vueFormulaire]);
    }

    /**
     * @Route("/ajouter/stage", name="pro_stages-ajouter-stage")
     * @IsGranted ("ROLE_USER")
     */
    public function ajouterStage(Request $request, EntityManagerInterface $manager): Response
    {
        $stage = new Stage();

        //Création du formulaire

        $formulaireStage=$this->createForm(StageType::class, $stage);

        $formulaireStage->handleRequest($request);

        
        if($formulaireStage->isSubmitted() && $formulaireStage->isValid())
        {
            $manager->persist($stage);
            $manager->flush();
        }
        
        dump($stage);


        //Représentation graphique du form
        $vueFormulaire = $formulaireStage->createView();

        return $this->render('pro_stages/ajouterStage.html.twig', ['vueFormulaire' => $vueFormulaire]);
    }
}
