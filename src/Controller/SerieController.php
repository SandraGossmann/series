<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/list/{page}', name: 'list', requirements: ['page'=>'\d+'], methods: 'GET')]
    public function list(SerieRepository $serieRepository, int $page = 1): Response
    {
        //on récupère toutes les series en passant par le repository
        //$series = $serieRepository->findAll();

        //utilisation de findBy avec un tableau de clause WHERE, ORDER BY
        //$series = $serieRepository->findBy(["status" => "ended"], ["popularity" => 'DESC'], 10);

        //récupération des 50 series les mieux notées
        //$series =$serieRepository->findBy([], ["vote" => "DESC"], 56);

        //dump($series);

        //méthode magique findByNomAttribut
        //$series2 = $serieRepository->findByStatus("ended");

        $nbSeriesTotal = $serieRepository->count([]);
        //ceil arrondit à l'entier supérieur
        $nbPagesMax = ceil($nbSeriesTotal / SerieRepository::SERIE_LIMITE);

        if($page >= 1 && $page <= $nbPagesMax){
            $series = $serieRepository->findBestSeries($page);
        } else throw $this->createNotFoundException("Oops, page not found");


        return $this->render('serie/list.html.twig', [
            //on envoie les données à la vue
            'series' => $series,
            'currentPage' => $page,
            'maxPage' => $nbPagesMax,
        ]);


    }

    #[Route('/add', name: 'add')]
    #[IsGranted("ROLE_USER")]
    public function add(SerieRepository $serieRepository, Request $request): Response
    {
        //renvoie une 403
        //$this->createAccessDeniedException();
        $serie = new Serie();
        $serieForm = $this->createForm(SerieType::class, $serie);
        //méthode qui extrait les infos de la requête et hydrate l'objet $serie
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()){
            //on sette manuellement la date de création
            //ajout d'un prepersist dans serie qui sette la date now
            //$serie->setDateCreated(new \DateTime());

            //on type $file pour pouvoir utiliser les méthodes + facilement (autocomplétion)
            /**
             * @var UploadedFile $file
             */
            //on demande d'extraire une info du formulaire, upload photo
            $file = $serieForm->get('poster')->getData();
            //on renomme le fichier -- uniqid génère un nombre aleatoire basé sur le timestamp
            //guessExtension récupère le type de fichier qqsoit son extension
            $newFileName = $serie->getName()."-".uniqid().".".$file->guessExtension();
            //on déplace le fichier pour le sauvegarder; 1er param=directory, 2e param=nom
            $file->move('img/posters/series', $newFileName);
            //on sette l'attribut poster avec le nouveau nom
            $serie->setPoster($newFileName);

            //suvegarde en bdd de la série
            $serieRepository->save($serie, true);
            $this->addFlash("success", "Serie added !");
            return $this->redirectToRoute('serie_show', ['id' => $serie->getId()]);
        }

        return $this->render('serie/add.html.twig', ['serieForm' => $serieForm->createView()]);
    }

#[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]

    public function show(int $id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);
        if(!$serie){
            //lance une erreur 404 si la série n'existe pas en bdd
            throw $this->createNotFoundException("Oops ! Serie not found !");
        }
        return $this->render('serie/show.html.twig', ["serie" => $serie]);
    }
//    #[Route('/{serie}', name: 'show', requirements: ['serie' => '\d+'])]
//    //Typer la variable comme un type entite appelle automatiquement la methode find
//    //en passant l'id il va directement chercher l'objet Serie correspondant
//    public function show(Serie $serie, SerieRepository $serieRepository): Response
//    {
//        return $this->render('serie/show.html.twig', ["serie" => $serie]);
//    }

#[Route('/remove/{id}', name: 'remove', requirements: ['id' => '\d+'])]
    public function remove(SerieRepository $serieRepository, int $id){
        //récupération de la série
        $serie = $serieRepository->find($id);
        if($serie){
            //suppression de la série
            $serieRepository->remove($serie, true);
            $this->addFlash("warning", "Serie Deleted !");
        } else {
            $this->createNotFoundException("This serie can't be deleted !");
        }
        return $this->redirectToRoute('serie_list');
    }

}
