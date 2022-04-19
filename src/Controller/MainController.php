<?php

namespace App\Controller;

use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function home(SiteRepository $siteRepository,SortieRepository $sortieRepository): Response
    {
        $sites=$siteRepository->findAll();
        $sorties=$sortieRepository->findAll();

        return $this->render('main/index.html.twig',[
            'sites'=>$sites,'sorties'=>$sorties
        ]);
    }
}
