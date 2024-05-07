<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MoviesController extends AbstractController
{
    public function __construct(
        private MovieRepository $movieRepository,
        private SerializerInterface $serializer
    ) {}

    #[Route('/movies', methods: ['GET'])]
    public function findMovies(Request $request, MovieRepository $movieRepository): JsonResponse
    {

        $genreId = $request->query->get('genreId');
        
       
        // Utilizza il repository per trovare i film in base al genere con la funzione findMoviesByGenre
        $movies = $movieRepository->findMoviesByGenre($genreId);
        

        // Serializza i risultati e restituisci la risposta JSON
        $data = $this->serializer->serialize($movies, "json", ["groups" => "default"]);
        return new JsonResponse($data, json: true);
    }
}
