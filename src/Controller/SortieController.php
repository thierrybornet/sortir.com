<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/creer/sortie", name="app_sortie_creer")
     */
    public function crerSortie(Request $request,EntityManagerInterface $entityManager,SortieRepository $sortieRepository,EtatRepository $etatRepository): Response
    {
        $user=$this->getUser();
        $site=$user->getSite();

        $sorite = new Sortie();
        $etat = $etatRepository->findOneBy(['libelle'=>'Crée']);

        $sorite->setSite($site);
        $sorite->setEtat($etat);
        $sorite->setAuteur($user);

        $sortieForm = $this->createForm(SortieType::class, $sorite);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid())
        {

            $entityManager->persist($sorite);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $this->addFlash('success', 'Votre Sortieà été crée avec succes!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('sortie/creer.html.twig',[
            'sortieForm'=>$sortieForm->createView()
        ]);
    }

    /**
     * @Route("/sortie/afficher/{id<[0-9]+>}", name="app_sortie_afficher")
     */
    public function afficher(Sortie $sortie): Response
    {

        return $this->render('sortie/afficher.html.twig',compact('sortie'));
    }
}

