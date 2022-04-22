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

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $sites=$siteRepository->findAll();


        $filtreForm=$this->createForm(FiltreSortieType::class);
        $filtreForm->handleRequest($request);

        $choixSite=$request->request->get('site');

        $choixInscrit=$request->request->get('organisateur');

        $inscrit=$request->request->get('inscrit');



        $sorties= $sortieRepository->findAll();

        if ($filtreForm->isSubmitted()) {
            if ($choixSite) {
                $sorties= $sortieRepository->findByExampleField($choixSite);

            }
            if ($choixInscrit) {
                $sorties= $sortieRepository->findOne($choixInscrit);

            }


        }





        return $this->render('main/index.html.twig',[
            'sites'=>$sites,'sorties'=>$sorties,'filtreForm'=>$filtreForm->createView(),
        ]);
    }
}
