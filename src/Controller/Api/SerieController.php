<?php

namespace App\Controller\Api;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/serie', name: 'api_serie_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'retrieve_all', methods: 'GET')]
    public function retrieveAll(SerieRepository $serieRepository): Response
    {
        $series = $serieRepository->findAll();
        return $this->json($series, 200, [], ["groups" =>"serie_api"]);

    }

    #[Route('/{id}', name: 'retrieve_one', methods: 'GET')]
    public function retrieveOne(int $id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);
        return $this->json($serie, 200, [], ['groups' => 'serie_api']);
    }

    #[Route('', name: 'add', methods: 'POST')]
    public function add(Request $request, SerializerInterface $serializer): Response
    {
        $data = $request->getContent();
        //serializer transforme la donnée json en instance de serie
        $serie = $serializer->deserialize($data, Serie::class, 'json');
        //TODO sauvegarder en bdd
        dd($serie);
        return $this->json('OK');
    }

    #[Route('/{id}', name: 'update', methods: 'PUT')]
    public function update(int $id): Response
    {
        //TODO update serie
    }
}
