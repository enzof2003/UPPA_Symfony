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
        $tupleFormation= $repositoryFormation->find($id);
        $stages=$repositoryStage->findByEntreprise($tupleFormation);
        $titreFormation=$tupleFormation->getNomLong();
        return $this->render('pro_stages/formations.html.twig', [
            'controller_name' => 'ProStagesController',
            'formationCherche' => $titreFormation,
            'titrePage' => 'Recherche par formation',
            'stages'=> $stages,
        ]);
        
    }
	
		/**
     * @Route("/stages/{id}", name="pro_stages-stages")
     */
    public function stages($id): Response
    {
		return $this->render('pro_stages/stages.html.twig',[
        'id' => $id,
        'titrePage' => 'Stage n°'.$id,
        'titreOffre' => 'Offre de test',
        'entreprise' => 'Entreprise',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce pulvinar a risus a varius. Aliquam id elementum arcu. Mauris non dapibus elit. Proin malesuada mauris et libero mollis, quis tristique justo aliquet. Mauris eu malesuada tellus. Nam sodales vitae mauris at consectetur. Sed sed arcu congue, elementum tortor id, fringilla mauris.',
        'formation' => 'DUT Info',
        'adrMail' => 'test@test.com',
        'siteWeb' => 'test.com',
        'secteur' => 'Debugger'
        ]);
    }
}
