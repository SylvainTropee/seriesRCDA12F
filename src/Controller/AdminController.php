<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard()
    {
        return $this->render('admin/dashboard.html.twig');
    }
}
