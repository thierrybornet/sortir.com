<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\AnnulationType;
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
    public function creerSortie(Request $request,EntityManagerInterface $entityManager,SortieRepository $sortieRepository,EtatRepository $etatRepository): Response
    {
        $user=$this->getUser();
        $site=$user->getSite();

        $sorite = new Sortie();
        $etat = $etatRepository->findOneBy(['libelle'=>'En création']);

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

    /**
     * @Route("/modifier/sortie/{id<[0-9]+>}", name="app_sortie_modifier")
     */
    public function modifierSortie(Request $request,EntityManagerInterface $entityManager,
                                   SortieRepository $sortieRepository,
                                   EtatRepository $etatRepository,
                                   $id): Response
    {
        $user=$this->getUser();
        $site=$user->getSite();

        $sorite =$sortieRepository->find($id);

        $etat = $etatRepository->findOneBy(['libelle'=>'En création']);

        $sorite->setSite($site);
        $sorite->setEtat($etat);
        $sorite->setAuteur($user);

        $sortieForm = $this->createForm(SortieType::class, $sorite);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid())
        {

            $env= $request->request->get('modifier');

            $publier='publier';
            $supprimer='supprimer';

            if($env=== $publier) {
                $etat = $etatRepository->findOneBy(['libelle'=>'Ouvert']);

                $sorite->setEtat($etat);
                $this->addFlash('success', 'Votre Sortie à été publiée avec succes!');
                $entityManager->persist($sorite);
                $entityManager->flush();
                return $this->redirectToRoute('app_home');

            }
            elseif ($env=== $supprimer) {

                $entityManager->remove($sorite);
                $entityManager->flush();
                $this->addFlash('success', 'Votre Sortie à été supprimée avec succes!');
                return $this->redirectToRoute('app_home');

            }

            $entityManager->persist($sorite);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $this->addFlash('success', 'Votre Sortie à été modifié avec succes!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('sortie/modifier.html.twig',[
            'sortieForm'=>$sortieForm->createView(),
            'sorite'=>$sorite
        ]);
    }


    /**
     * @Route("/sortie/inscription/{id<[0-9]+>}", name="app_sortie_inscription")
     */
    public function inscription (SortieRepository $sortieRepository,$id,EntityManagerInterface $entityManager,EtatRepository $etatRepository): Response
    {
        $user=$this->getUser();
        $etat = $etatRepository->findOneBy(['libelle'=>'Fermé']);

        $sortie =$sortieRepository->find($id);

        $sortie->addUsersInscrit($user);




        if ($sortie->getNbInscriptionsMax() === $sortie->getUsersInscrits()->count()) {
            $sortie->setEtat($etat);
        }


        $entityManager->persist($sortie);
        $entityManager->flush();


        $this->addFlash('success', 'Vous vous etes inscrit avec succés!');

        return $this->redirectToRoute('app_home');
    }



    /**
     * @Route("/sortie/publication/{id<[0-9]+>}", name="app_sortie_publication")
     */
    public function publication (SortieRepository $sortieRepository,
                                 $id,
                                 EntityManagerInterface $entityManager,
                                 EtatRepository $etatRepository): Response
    {


        $sorite =$sortieRepository->find($id);
        $etat = $etatRepository->findOneBy(['libelle'=>'Ouvert']);

        $sorite->setEtat($etat);
        $entityManager->flush();


        $this->addFlash('success', 'Votre sortie à été publiée avec succés!');

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/sortie/desister/{id<[0-9]+>}", name="app_sortie_desister")
     */
    public function desister (SortieRepository $sortieRepository,$id,EntityManagerInterface $entityManager,EtatRepository $etatRepository): Response
    {
        $user=$this->getUser();
        $etat = $etatRepository->findOneBy(['libelle'=>'Ouvert']);


        $sortie =$sortieRepository->find($id);

        $sortie->removeUsersInscrit($user);

        if ($sortie->getNbInscriptionsMax() > $sortie->getUsersInscrits()->count()) {
            $sortie->setEtat($etat);
        }

        $entityManager->persist($sortie);
        $entityManager->flush();


        $this->addFlash('success', 'Vous vous etes désisté avec succés!');

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/annuler/sortie/{id<[0-9]+>}", name="app_sortie_annuler")
     */
    public function annulerSortie(Request $request,
                                  EntityManagerInterface $entityManager,
                                  SortieRepository $sortieRepository,
                                  EtatRepository $etatRepository,
    $id): Response
    {



        $sorite =$sortieRepository->find($id);
        $etat = $etatRepository->findOneBy(['libelle'=>'Annulée']);



        $annulerSortieForm = $this->createForm(AnnulationType::class, $sorite);
        $annulerSortieForm->handleRequest($request);

        if ($annulerSortieForm->isSubmitted() && $annulerSortieForm->isValid())
        {
            $env=$request->request->get('annuler');
            $annuler='annuler';
            $enregistrer='enregistrer';



            if ($env== $annuler) {
                return $this->redirectToRoute('app_home');

                // do anything else you need here, like send an email
            }



            $sorite->setEtat($etat);
            $entityManager->persist($sorite);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $this->addFlash('success', 'Votre Sortie à été annulée avec succes!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('sortie/annulerSortie.html.twig',[
            'annulerSortieForm'=>$annulerSortieForm->createView(),'sortie'=>$sorite
        ]);
    }



}

