<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SeriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/series', name: 'serie_')]
class SeriesController extends AbstractController
{
    #[Route('', name: 'list')]
    public function list(SeriesRepository $serieRepository): Response // docrine va etre declare
    {
        // todo : aller cherche les series en BDD
        //$series =$serieRepository FindBy([],['popularity'=>'DESC'], 30); // findBy (function )insendence de tableux // valeur par defaultdump($series);
            $series = $serieRepository->findBestSeries();
        //$series = $serieRepository->findAll();
        dump($series); // docrine va etre declare

        return $this->render('serie/list.html.twig',[
            'series'=>$series // une cles serise et ca valeur // docrine va etre declare
        ]);
    }

    #[Route('/details/{id}', name: 'details')]
    public function details(int $id, SeriesRepository $seriesRepository): Response
    {
            // todo : aller chercher les séries en BDD


            $serie = $seriesRepository->find($id);
            if(!$serie) //
            throw $this->createNotFoundException('Serie does not exist');

            return $this->render('serie/details.html.twig',
            [
                //passer la serie à twig
                'serie'=> $serie
            ]);
    }
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Serie $serie, SeriesRepository $seriesRepository,
                           EntityManagerInterface $entityManager,
                           Request $request): Response {
        {       //serie= $serueRepository ->find($id);
                //test token twig // pour security ~tild pour twig    et . php  + // function del Request$request
                if ($this->isCsrfTokenValid('delete'.$serie->getId(), $request->get('_token')))

                // $serie = $seriesRepository->find($id);
                // suppression de touts les saisons de la série
                foreach ($serie->getSeasons() as $season) // supristion manuel avec foreach
                $entityManager->remove($season);
        }

        // supprission de la serie
        $entityManager->remove($serie); // suprime
        $entityManager->flush();

        return $this->redirectToRoute('serie_list');


    }
    #[Route('/create', name: 'create')]
    public function  create(Request $request, EntityManagerInterface $entityManager): Response{

        //  utilise debog pour teste si ca march >>>>
        // etape 1 : cree un instance de Serie pour formulaire
        $serie = new Serie();

        // etape 2: cree ubne instane de SerieType
        $serieForm = $this->createForm (SerieType::class, $serie); // creatForm >> sozdaet formu
        // pour trasmetre a la base de donne
        // et dans le car erreure affiche text erreur // au message flash
        // submit pour avoir trete les donne donne formulaire tretment dans meme >>function create

         dump($serie);// pour affiche avent request Mysql
         //injection les donnees du formulaire dans l'entite $serie
         $serieForm->handleRequest($request);
         dump($serie);// pour affiche la defirance

            // test si le formulaire a ete soumis
        if($serieForm->isSubmitted() && $serieForm->isValid()){ // pour regets  >> && $serieForm->isValid()
            // pour inregistre new client au infoisSubmite  ajoute// dans function EntityManagerInterface $entityManager
            $serie->setDateCreated(new \DateTime('now'));
            $entityManager->persist($serie);    //enregistre
            $entityManager->flush();            // envoie

            // message flach pour affich a l'utilistaeur que tout est bien passe
            $this->addFlash('success', 'Serie added !!! ');//  attention bien mettre (type) !!!!
            // retourne un tableu 'serie' get Id
            return $this ->redirectToRoute('serie_details', ['id' => $serie->getId()]);
        }


         dump($request);
            //ostonavlivaetsya  na dom
            //dd($request);

        return $this ->render('/serie/create.html.twig',[
            // etape 3: serieForm  dans twig  6.1 symfony nujno dobavit ->createView() v kontse
            'serieForm'=>$serieForm]);




    }

    #[Route('/demo', name: 'demo')] // base de donne
    public function demo (EntityManagerInterface $entityManager){ // envoier dans base de done
        $serie = new Serie();
        $serie->setName('pif');
        $serie->setBackdrop('test');
        $serie->setPoster('poster');
        $serie->setDateCreated(new \DateTime()); // date de creation doctrine
        $serie->setFirstAirDate(new \DateTime('-1year'));// date de modification
        $serie->setLastAirDate(new \DateTime('-6year'));// date de modification
        $serie->setGenre('Fantastyc');
        $serie->setOverview('blabla');
        $serie->setVote(1.5);
        $serie->setPopularity(5.60);
        $serie->setStatus('canceled');
        $serie->setTmdbId(1234);

        dump($serie); //affiche
        //enregistre la serie en BDD
        $entityManager->persist($serie);// 'faire un commit envoie creation dans base de done

        $entityManager->flush();// commit // confirme envoie
        dump($serie);// affiche avec dump

        // $entityManager->remove($serie); //suprime
        // $entityManager->flush();//commit

        // modification
        $serie->setGenre('comic');  //change genre + $entityManager->persist($serie)
        $entityManager->flush();//commit

        return $this->render('serie/create.html.twig');
    }
}
