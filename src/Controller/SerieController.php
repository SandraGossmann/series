<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(SerieRepository $serieRepository): Response
    {
        //on récupère toutes les series en passant par le repository
        //$series = $serieRepository->findAll();

        //utilisation de findBy avec un tableau de clause WHERE, ORDER BY
        //$series = $serieRepository->findBy(["status" => "ended"], ["popularity" => 'DESC'], 10);

        //récupération des 50 series les mieux notées
        $series =$serieRepository->findBy([], ["vote" => "DESC"], 50);

        dump($series);

        return $this->render('serie/list.html.twig', [
            //on envoie les données à la vue
            'series' => $series
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(SerieRepository $serieRepository, EntityManagerInterface $entityManager): Response
    {
        $serie = new Serie();

//        //on sette les infos de la série
//        $serie->setName("The Office")
//                ->setBackdrop("backdrop.png")
//                ->setDateCreated(new \DateTime())
//                ->setGenres("Comedy")
//                ->setFirstAirDate(new \DateTime('2022-02-02'))
//                ->setLastAirDate(new \DateTime("-6 month"))
//                ->setPopularity(850.52)
//                ->setPoster("poster.png")
//                ->setTmdbId(123456)
//                ->setVote(8.5)
//                ->setStatus("Ended");
//        dump($serie);
//
//        //enregistrement de la série en bdd
//        $serieRepository->save($serie);
//        dump($serie);
//
//        $serie->setName("The last of us");
//        $serieRepository->save($serie, true);
//        dump($serie);
        $serie2 = new Serie();
        $serie2->setName("Le magicien")
            ->setBackdrop("backdrop.png")
            ->setDateCreated(new \DateTime())
            ->setGenres("Comedy")
            ->setFirstAirDate(new \DateTime('2022-02-02'))
            ->setLastAirDate(new \DateTime("-6 month"))
            ->setPopularity(850.52)
            ->setPoster("poster.png")
            ->setTmdbId(123456)
            ->setVote(8.5)
            ->setStatus("Ended");


        //utilisation de EntityManagerInterface
        $entityManager->persist($serie2);
        $entityManager->flush();

        $serieRepository->remove($serie2, true);
        dump($serie2);

        //TODO créer un formulaire d'ajout de série
        return $this->render('serie/add.html.twig');
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);

        return $this->render('serie/show.html.twig', ["serie" => $serie]);
    }

}
