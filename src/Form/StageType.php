<?php

namespace App\Form;

use App\Entity\Stage;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class StageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('descMission', null,['label' => 'Description Mission'])
            ->add('emailContact')
            ->add('entreprise', EntrepriseType::class)
            ->add('formations',EntityType::class,array (
                'class'=>Formation::class,
                'choice_label'=>function(Formation $formation)
                {return $formation->getNomLong();},
                'multiple'=>true,
                'expanded'=>true,
                'label' => 'Diplômes',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stage::class,
        ]);
    }
}
