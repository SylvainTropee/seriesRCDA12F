<?php

namespace App\Controller\Api;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/series', name: 'api_series_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'all', methods: 'GET')]
    public function retrieveAll(
        SerieRepository $serieRepository
    ): Response
    {
        $series = $serieRepository->findAll();
        return $this->json($series, Response::HTTP_OK, [], ['groups' => 'serie']);
    }

    #[Route('/{id}', name: 'one', methods: 'GET')]
    public function retrieveOne(
        int $id,
        SerieRepository $serieRepository,
        SerializerInterface $serializer
    ): Response
    {

        //TODO renvoyer la série en JSON
        $serie = $serieRepository->find($id);
        //json_decode() / json_encode()
        //$json = json_encode($serie);

        //serializer + JsonResponse équivalent à $this->json()
        //$json= $serializer->serialize($serie, 'json', ['groups' => 'serie']);
        //return new JsonResponse($json, Response::HTTP_OK);

        return $this->json($serie, Response::HTTP_OK, [], ['groups' => 'serie']);
    }

    #[Route('', name: 'create', methods: 'POST')]
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager
    ): Response
    {
        //extraie le body de la requête
        $data = $request->getContent();

        //récupération d'un objet anonyme
        //$data = json_decode($data);
        //récupération d'un tableau associatif
        //$data = json_decode($data, true);

        //permet de transformer le json dans un format entité
        $serie = $serializer->deserialize($data, Serie::class, 'json');
        $serie->setDateCreated(new \DateTime());

        $entityManager->persist($serie);
        $entityManager->flush();

        return $this->json(
            $serie,
            Response::HTTP_CREATED,
            [
                "Location" => $this->generateUrl(
                    'api_series_one',
                    ['id' => $serie->getId()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ],
            ['groups' => 'serie']
        );
    }


    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(int $id): Response
    {
        //TODO update une série
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        //TODO supprimer une série
    }

}
