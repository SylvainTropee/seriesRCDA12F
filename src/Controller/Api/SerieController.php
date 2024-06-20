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
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
        int                 $id,
        SerieRepository     $serieRepository,
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
        Request                $request,
        SerializerInterface    $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface     $validator
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

        $errors = $validator->validate($serie);
        if (count($errors) > 0) {
            //$errorsJson = $serializer->serialize($errors, 'json');
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

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
    public function update(int                    $id,
                           Request                $request,
                           SerieRepository        $serieRepository,
                           EntityManagerInterface $entityManager
    ): Response
    {
        //j'extraie le body de la requête
        $json = $request->getContent();

        //transforme en tableau ou objet
        $data = json_decode($json, true);

        $serie = $serieRepository->find($id);

        //je modifie l'attribut nbLike
        if($data['like'] == 1){
            $serie->setNbLike($serie->getNbLike() + 1);
        }else{
            $serie->setNbLike($serie->getNbLike() - 1);
        }

        $entityManager->persist($serie);
        $entityManager->flush();

        //je retourne l'objet modifié
        return $this->json($serie, Response::HTTP_OK, [], ['groups' => 'serie']);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        //TODO supprimer une série
    }

}
