<?php

namespace App\Form;

use App\Entity\Joueur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewJoueurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numeroLicence', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'text-primary mb-0',
                ],
            ])
            ->add('typeLicence', ChoiceType::class, [
                'choices' => Joueur::TYPELICENCE,
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'text-primary mb-0',
                ],
            ])
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'text-primary mb-0',
                ],
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'text-primary mb-0',
                ],
            ])
            ->add('points', IntegerType::class, [
                'label' => 'Nombre de points',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'text-primary mb-0',
                ],
            ])
            ->add('add', SubmitType::class, [
                'label' => 'Ajouter ',
                'attr' => [
                    'class' => 'btn btn-primary mr-2',
                ],
            ])
            ->add('addAndStartAgain', SubmitType::class, [
                'label' => 'Ajouter et créer un nouveau',
                'attr' => [
                    'class' => 'btn btn-secondary mr-2',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Joueur::class,
        ]);
    }
}
