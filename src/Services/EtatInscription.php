<?php

namespace App\Services;

use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;

class EtatInscription
{


    protected $entityManager;
    protected $etatRepo;
    protected $sortieRepository;



    public function __construct(EntityManagerInterface $entityManager,EtatRepository $etatRepo,SortieRepository  $sortieRepository) {
        $this->entityManager=$entityManager;
        $this->etatRepo=$etatRepo;
        $this->sortieRepository=$sortieRepository;

    }


}