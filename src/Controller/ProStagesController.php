<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Stage;
use App\Entity\Entreprise;
use App\Entity\Formation;

class ProStagesController extends AbstractController
{
    /**
     * @Route("/", name="pro_stages-accueil")
     */
    public function index(): Response
    {
        $repositoryStage=$this->getDoctrine()->getRepository(Stage::class);
        $stages=$repositoryStage->findAll();
        return $this->render('pro_stages/index.html.twig', [
            'controller_name' => 'ProStagesController',
            'titrePage' => 'Page d\'accueil',
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
        $stages=$repositoryStage->findByEntreprise($tupleEntreprise);
        $titreEntreprise=$tupleEntreprise->getNom();
        return $this->render('pro_stages/entreprises.html.twig', [
            'controller_name' => 'ProStagesController',
            'entrepriseCherche' => $titreEntreprise,
            'titrePage' => 'Recherche par entreprise',
            'stages'=> $stages,
        ]);
        
    }
	
		/**
     * @Route("/formations/{id}", name="pro_stages-formations")
     */
    public function formations($id): Response
    {
        $repositoryStage=$this->getDoctrine()->getRepository(Stage::class);
        $repositoryFormation=$this->getDoctrine()->getRepository(Formation::class);
        $tupleFormation=$repositoryFormation->find($id);
        //$stages=$repositoryStage->findAll();
        $stages=$tupleFormation->getStages();
        return $this->render('pro_stages/formations.html.twig', [
            'controller_name' => 'ProStagesController',
            'formationCherche' => $tupleFormation,
            'titrePage' => 'Recherche par formation',
            'stages'=> $stages,
        ]);
        
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
}
