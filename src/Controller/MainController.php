<?php

namespace App\Controller;

use App\Services\CallApiServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    //avec les attributs (à partir de php 8)
    #[Route('/home', name: 'main_home')]
    public function home(): Response
    {
        $username = "Sandra";
        $serie = ['title' => 'Community', 'year' => 'Ouf', 'platform' => 'NBC'];
        //la clé devient le nom de la variable côté twig
        return $this->render('main/home.html.twig', ["name" => $username, "serie" => $serie]);
    }

    //avec les annotations (avant php 8)
    /**
     * @Route("/test", name="main_test")
     */
    public function test(CallApiServices $api): Response
    {
        $communes = $api->getCities();
        dd($communes);
        return $this->render('main/test.html.twig');
    }

//    public function trucAction()
//    {
//        die('Truc');
//    }
}
