<?php

namespace App\Form;

use App\Entity\Equipe;
use App\Entity\Joueur;
use App\Repository\JoueurRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifierEquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', IntegerType::class, [
                'label' => 'Numéro équipe'
            ])
            ->add('niveau', ChoiceType::class, [
                'choices' => Equipe::NIVEAU
            ])
            ->add('priorite', IntegerType::class, [
                'label' => 'Importance',
                'help' => 'Gérer le niveau d\'importance permet d\'ajuster l\'autocomplétion de l\'application. 
                Plus le nombre est grand, plus vous accordez de l\'importance à cette équipe',
            ])
            ->add('joueurs', EntityType::class, [
                'class' => Joueur::class,
                'query_builder' => function (JoueurRepository $joueurRepository) {
                    return $joueurRepository->createQueryBuilder('j')->orderBy('j.points', 'DESC');
                },
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
        ]);
    }
}
