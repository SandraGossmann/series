<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        //TODO récupérer la liste des séries en bdd
        return $this->render('serie/list.html.twig');
    }

    #[Route('/add', name: 'add')]
    public function add(): Response
    {
        //TODO créer un formulaire d'ajout de série
        return $this->render('serie/add.html.twig');
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        dump($id);
        //TODO récupérer les infos de la série
        return $this->render('serie/show.html.twig');
    }

}
