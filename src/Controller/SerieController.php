<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/series', name: 'series_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'list')]
    public function list(SerieRepository $serieRepository): Response
    {
        $series = $serieRepository->findAll();

        return $this->render('series/list.html.twig', [
                "series" => $series
            ]
        );
    }

    #[Route('/{id}', name: 'detail', requirements: ['id' => '\d+'])]
    public function detail(SerieRepository $serieRepository, int $id): Response
    {
        $serie = $serieRepository->find($id);

        if(!$serie){
            throw $this->createNotFoundException("Ooops ! Series not found !");
        }

        return $this->render('series/detail.html.twig', [
            'serie' => $serie
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $serie = new Serie();
        $serie
            ->setName("House of dragons")
            ->setBackdrop("backdrop.png")
            ->setDateCreated(new \DateTime())
            ->setGenres("Fantasy")
            ->setFirstAirDate(new \DateTime("-2 year"))
            ->setLastAirDate(new \DateTime("-1 year"))
            ->setPopularity(800.00)
            ->setPoster("poster.png")
            ->setStatus("returning")
            ->setTmdbId(12345)
            ->setVote(8);

        dump($serie);
        //mets en file d'attente avant enregistrement
        $entityManager->persist($serie);
        //j'éxécute les requêtes
        $entityManager->flush();
        dump($serie);

        $serie->setName("Pokemon XYZ");
        $entityManager->persist($serie);
        $entityManager->flush();

        dump($serie);

        $entityManager->remove($serie);
        $entityManager->flush();


        //TODO renvoyer un formulaire de création de série
        return $this->render('series/create.html.twig');
    }


}










