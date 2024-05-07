<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
//new
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MoviesController extends AbstractController
{
    public function __construct(
        private MovieRepository $movieRepository,
        //serializza gli oggetti film in formato JSON
        private SerializerInterface $serializer
    ) {}

    #[Route('/movies', methods: ['GET'])]

    public function list(Request $request): JsonResponse
    {
        // richiamo il parametro sortby tramite metodo get all'url, uso recente come valore predefinito
        $sortBy = $request->query->get('sortBy', 'recente');

        // verifico se c'è un cretireio di ordinamento
        if ($sortBy === 'recente') {
        //ordinamento personalizzato secondo i seguenti parametri
        $movies = $this->movieRepository->createQueryBuilder('m')
            ->addOrderBy('m.releaseDate', 'DESC')
            ->orderBy('m.year', 'DESC')
            ->addOrderBy('m.rating', 'DESC')
            ->getQuery()
            ->getResult();
        } else {
        // se il criterio di ordinamento è sbagliato -> restituisco errore 400
            return new JsonResponse(['error' => 'Criterio di ordinamento non valido'], 400);
        }
        //serializer dell'elenco dei film in JSON
        $data = $this->serializer->serialize($movies, "json", ["groups" => "default"]);
        // restituisco JSON con film ordinati
        return new JsonResponse($data, json: true);
    }
}
