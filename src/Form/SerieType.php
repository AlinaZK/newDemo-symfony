<?php

namespace App\Form;

use App\Entity\Serie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,['label' =>'Title'])// [] pour modifier // date le client ne doit pas voir
            ->add('overview')
            ->add('status', ChoiceType::class,['choices'=>[ //choise type determine list derulont et les chois dans la liste derulent
                'Cancelled'=>'Cancelled',
                'Ended'=> 'Ended',
                'Returning'=>'Returning'
            ], //choix proposes dans list déroulante
                'multiple'=>false
            ])
            ->add('vote')
            ->add('popularity')
            ->add('genre')                              //beforeTypeType//DateType poetomy
            ->add('firstAirDate',DateType::class, [// comment faire pour present dans lable date // >>use Symfony\Component\Form\Extension\Core\Type\DateType;
                'html5'=> true,
                'widget'=> 'single_text'
            ])  // caracteristique d(un champ date avec calendrier

            ->add('lastAirDate')
            ->add('backdrop')
            ->add('poster')
            ->add('tmdbId')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serie::class,
        ]);
    }
}
