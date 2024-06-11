<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/series', name: 'series_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'list')]
    public function list(): Response
    {
        //TODO renvoyer la liste des séries
        return $this->render('series/list.html.twig');
    }

    #[Route('/{id}', name: 'detail', requirements: ['id' => '\d+'])]
    public function detail(int $id): Response
    {
        //TODO renvoyer une série
        return $this->render('series/detail.html.twig');
    }

    #[Route('/create', name: 'create')]
    public function create(): Response
    {
        //TODO renvoyer un formulaire de création de série
        return $this->render('series/create.html.twig');
    }



}










