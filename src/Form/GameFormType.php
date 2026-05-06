<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Team;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;


class GameFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'label'  => 'Date du match'
            ])
            ->add('opposing_team' , TextType::class, [
                'label'=> "Nom de l'équipe adverse",
                'required' => false,
                'attr'=> [
                    'placeholder'=> "Renseigner le nom de l'équipe adverse",
                    'maxlenght'=>255
                ],
                'constraints'=> [
                    new Length(min:3, max:255)
                ]
            ])
            ->add('place', TextType::class, [
                'label'=> 'Lieu du match',
                'required' => false,
                'attr'=> [
                    'placeholder'=>'Renseigner un lieu',
                    'maxlenght'=>255
                ],
                'constraints'=> [
                    new Length(min:3, max:255)
                ]
            ])
            ->add('score_home', IntegerType::class, [
                'label' => "Score domicile",
                'required' => false,
                'attr' => [
                 'min' => 0,              
                 'max' => 99,             
                'step' => 1,             
                'placeholder' => '-',    
                'inputmode' => 'numeric', 
           
        ]])
            ->add('score_away',  IntegerType::class, [
                'label' => "Score exterieur",
                'required' => false,
                'attr' => [
                 'min' => 0,              
                 'max' => 99,             
                'step' => 1,             
                'placeholder' => '-',    
                'inputmode' => 'numeric', 
            
        ]])
            ->add('coach_observation', TextareaType::class, [
                'label'=> "Observations du coach",
                'required' => false,
                'attr' => [
                'rows' => 10, 
                'placeholder' => 'Ex: Bonne intensité en 1ère mi-temps, manque de réalisme devant le but...',
        
    ],
            ])
            ->add('team', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'name',
            ])
            ->add('coach', EntityType::class, [
                'class' => User::class,
                  'choice_label' => function (User $user) {
                return $user->getFirstName() . ' ' . $user->getName();
                },
                'label' => 'Coach référent'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
