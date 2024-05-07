<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 *
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * $releaseDateSorting sort della colonna releaseDate
     * $ratingSorting sort della colonna rating
     * $genreId id del genere selezionato
    * @return Movie[] Returns an array of Movie objects
    */
    public function findMoviesByGenre($genreId)
    {
        // Creo query builder personalizzata per i movie
        $movies= $this->createQueryBuilder('m')
            ->leftJoin('m.movieGenres', 'mg');
            
            
    
        // user story 2: aggiunta filtro per genere
            if ($genreId !== '') {
                $movies->andwhere('mg.genre = :genreId')
                    ->setParameter('genreId', $genreId);

            }
            //userstory 1: aggiunto ordinamento per data di rilascio e rating - ho aggiunto anche l'anno per refuso in alcune releaseDate
            
            return $movies
            ->addOrderBy('m.releaseDate', 'DESC')
            ->orderBy('m.year', 'DESC')
            ->addOrderBy('m.rating', 'DESC')->getQuery()->getResult();
    }

    //    /**
    //     * @return Movie[] Returns an array of Movie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Movie
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
