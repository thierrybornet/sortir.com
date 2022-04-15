<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VilleController extends AbstractController
{
    /**
     * @Route("/ville", name="app_ville")
     */
    public function index(Request $request, EntityManagerInterface $entityManager,VilleRepository $repo): Response
    {
        $villes=$repo->findAll();
        $ville=new Ville();
        $villeForm=$this->createForm(VilleType::class,$ville);

        $villeForm->handleRequest($request);

        if ($villeForm->isSubmitted() && $villeForm->isValid())
        {

            $entityManager->persist($ville);
            $entityManager->flush();

            $this->addFlash('success','Your wish has been added succufully');

            return $this->redirectToRoute('app_ville');



    }

        return $this->render('ville/index.html.twig',[
            'villeForm'=>$villeForm->createView(),'villes'=>$villes
        ]);

    }


    /**
     * @Route("/supprimerVille/{id<[0-9]+>}", name="app_supprimer_ville")
     */
    public function supprimer(VilleRepository $repo,Ville $ville): Response
    {
        $repo->remove($ville);

        return $this->redirectToRoute('app_ville');



    }


    /**
     * @Route("/modifierVille/{id<[0-9]+>}", name="app_modifier_ville")
     */

    public function modifier(EntityManagerInterface $entityManager,Ville $ville): Response
    {



        //return $this->redirectToRoute('app_ville');

        return $this->render('ville/modifier.html.twig');

    }

}


