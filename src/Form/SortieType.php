<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateDebut',\Symfony\Component\Form\Extension\Core\Type\DateTimeType::class,[
                'html5'=>true,
                'date_widget'=>'single_text',
                'time_widget'=>'single_text'
            ])
            ->add('duree')
            ->add('dateCloture',DateType::class,[
                'html5'=>true,
                'widget'=>'single_text'
            ])
            ->add('descriptioninfos')
            ->add(
                'ville',

                EntityType::class,

                [

                    'mapped' => false,

                    'class' => Ville::class,

                    'choice_label' => 'nom',

                ]

            )
            ->add('lieu',null,["choice_label"=>"nom"])




            ->add('nbInscriptionsMax')
            ->add('urlPhoto')





        ;




    }




    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
