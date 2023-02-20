<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    //avec les attributs (à partir de php 8)
    #[Route('/home', name: 'main_home')]
    public function home(): Response
    {
        return $this->render('main/home.html.twig');
    }

    //avec les annotations (avant php 8)
    /**
     * @Route("/test", name="main_test")
     */
    public function test(): Response
    {
        return $this->render('main/test.html.twig');
    }

//    public function trucAction()
//    {
//        die('Truc');
//    }
}
