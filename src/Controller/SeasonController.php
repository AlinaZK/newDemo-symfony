<?php

namespace App\Controller;

use App\Entity\Season;
use App\Form\SeasonType;
use App\Repository\SeasonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SeasonController extends AbstractController
{
    #[Route('/season/crate', name: 'season_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response //\Symfony\Component\HttpFoundation\Request
    {

        $season = new Season();
        $seasonForm = $this->createForm(SeasonType::class,$season);
        // hydrate l'istnace de season avec mes dp,,es de ma request ' on valorise'
        $seasonForm ->handleRequest($request);

        if ($seasonForm ->isSubmitted() && $seasonForm->isValid()){
            $season->setDateCreated(new \DateTime());
            $season->setDateModified(new \DateTime()); //EntityManagerInterface $entityManager
            $entityManager->persist($season); //preparation envoi persist
            $entityManager->flush(); //envoie

            $this->addFlash('success', 'Season added!'); // message flash
            return $this->redirectToRoute('serie_list'); // ajoute obyazatelno return reonvoie en page (serie_list)
        }
        // 1 remplase ____index par create
        // 2 remplace SeasonController  par form
        return $this->render('season/create.html.twig', [
            'seasonForm' => $seasonForm,
        ]);
    }

    #[Route('/season/disscoiate', name: 'season_disscoiate')]
    public function  disscoiateSeasonWithSerie (SeasonRepository $seasonRepository){
        $season = $seasonRepository->find(10);
        $season ->setSerie(null);
        $series = [];
        return $this->render('serie/list.html.twig',[
            'series' => $series
        ]);
    }
}
