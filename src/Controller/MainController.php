<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{

//    /**
//     * autre possibilité d'écriture pour les version inférieurs à PHP 8
//     * @Route('main', name='app_main')
//     */

    #[Route('/home', name: 'main_home')]
    #[Route('', name: 'main_home_2')]
    public function home(): Response
    {
        return $this->render('main/home.html.twig');
    }

    #[Route('/test', name: 'main_test')]
    public function test(): Response
    {
        return $this->render('main/test.html.twig');
    }



}
