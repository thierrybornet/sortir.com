<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\SiteType;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/admin/site", name="app_site")
     */
    public function index(Request $request,EntityManagerInterface $entityManager,SiteRepository $siteRepository): Response
    {
        $sites=$siteRepository->findAll();
        $site=new Site();
        $siteForm=$this->createForm(SiteType::class,$site);

        $siteForm->handleRequest($request);

        if ($siteForm->isSubmitted() && $siteForm->isValid())
        {

            $entityManager->persist($site);
            $entityManager->flush();


            return $this->redirectToRoute('app_site');



        }
        return $this->render('site/index.html.twig',[
            'siteForm'=>$siteForm->createView(),'sites'=>$sites
        ]);
    }

    /**
     * @Route("/supprimerSite/{id<[0-9]+>}", name="app_supprimer_site")
     */
    public function supprimer(SiteRepository $repo,Site $site): Response
    {
        $repo->remove($site);

        return $this->redirectToRoute('app_site');



    }
}
