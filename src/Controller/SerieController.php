<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SerieController extends AbstractController
{
    #[Route('/serie/list', name: 'serie_list')]
    public function list(): Response
    {
        //TODO récupérer la liste des séries en bdd
        return $this->render('serie/list.html.twig', );
    }

    #[Route('/serie/add', name: 'serie_add')]
    public function add(): Response
    {
        //TODO créer un formulaire d'ajout de série
        return $this->render('serie/add.html.twig', );
    }
}
