<?php

namespace App\Controller;

use App\Form\AnnulationType;
use App\Form\FiltreSortieType;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function home(SiteRepository $siteRepository,
                         SortieRepository $sortieRepository,

    Request $request): Response
    {
        $sites=$siteRepository->findAll();
        $sorties=$sortieRepository->findAll();


        $filtreForm=$this->createForm(FiltreSortieType::class);
        $filtreForm->handleRequest($request);

        if ($filtreForm->isSubmitted() && $filtreForm->isValid()) {
            $choixSite=$request->request->get('site');

            if ($choixSite) {

                $sorties= $sortieRepository->findBy([],[$choixSite=>'DESC']);

                dd($sorties);

            }


            return $this->redirectToRoute('app_home',[
                'sorties'=>$sorties
            ]);
        }

        return $this->render('main/index.html.twig',[
            'sites'=>$sites,'sorties'=>$sorties,'filtreForm'=>$filtreForm->createView()
        ]);
    }
}
