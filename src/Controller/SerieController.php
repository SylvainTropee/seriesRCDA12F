<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/series', name: 'series_')]
class SerieController extends AbstractController
{
    #[Route('/{page}', name: 'list', requirements: ['page' => '\d+'])]
    public function list(
        SerieRepository $serieRepository,
        int             $page = 1
    ): Response
    {
        if($page < 1){
            $page = 1;
        }

        $nbSeriesMax = $serieRepository->count([]);
        $maxPage = ceil($nbSeriesMax / Serie::SERIE_PER_PAGE);
        if($page > $maxPage){
            $page = $maxPage;
        }

        $series = $serieRepository->findBestSeries($page);
//        $series = $serieRepository->findBy([], ["popularity" => "DESC"], 50, 0);

//        $series = $serieRepository->findBestSeries();

        return $this->render('series/list.html.twig', [
                "series" => $series,
                'currentPage' => $page,
                'maxPage' => $maxPage
            ]
        );
    }

    #[Route('/detail/{id}', name: 'detail', requirements: ['id' => '\d+'])]
    public function detail(SerieRepository $serieRepository, int $id): Response
    {
        $serie = $serieRepository->find($id);

        if (!$serie) {
            throw $this->createNotFoundException("Ooops ! Series not found !");
        }

        return $this->render('series/detail.html.twig', [
            'serie' => $serie
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(
        EntityManagerInterface $entityManager,
        Request                $request
    ): Response
    {
        //créé une instance de l'entité
        $serie = new Serie();
        //création du formulaire associé a l'instance de serie
        $serieForm = $this->createForm(SerieType::class, $serie);

        //extraie des informations de la requête HTTP
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {
            /**
             * @var UploadedFile $file
             */
            //récupération du fichier de type UploadedFile
            $file = $serieForm->get('poster')->getData();
            //création de son nom
            $newFilename = $serie->getName() . '-' . uniqid() . '.' . $file->guessExtension();
            //sauvegarde dans le bon répertoire en le renomant
            $file->move($this->getParameter('serie_poster_directory'), $newFilename);
            //setté le nouveau nom dans l'objet
            $serie->setPoster($newFilename);
            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash('success', 'Series added !');
            return $this->redirectToRoute('series_detail', ['id' => $serie->getId()]);
        }
        //TODO renvoyer un formulaire de création de série
        return $this->render('series/create.html.twig', [
            'serieForm' => $serieForm
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function update(
        EntityManagerInterface $entityManager,
        Request                $request,
        SerieRepository        $serieRepository,
        int                    $id
    ): Response
    {
        $serie = $serieRepository->find($id);

        if (!$serie) {
            throw $this->createNotFoundException('Ooops ! Series not found !');
        }

        $serieForm = $this->createForm(SerieType::class, $serie);
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {

            $serie->setDateModified(new \DateTime());

            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash('success', 'Series updated !');
            return $this->redirectToRoute('series_detail', ['id' => $id]);
        }

        return $this->render('series/update.html.twig', [
            'updateSerieForm' => $serieForm
        ]);

    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(
        EntityManagerInterface $entityManager,
        SerieRepository        $serieRepository,
        int                    $id
    ): Response
    {

        $serie = $serieRepository->find($id);

        if (!$serie) {
            throw $this->createNotFoundException("Serie not found !");
        }

        $entityManager->remove($serie);
        $entityManager->flush();
        $this->addFlash('success', 'Series deleted !');

        return $this->redirectToRoute('series_list');
    }

}










