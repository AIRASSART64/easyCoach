<?php

namespace App\Form;

use App\Entity\Training;
use App\Entity\User;
use App\Enum\TrainingTheme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class TrainingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime',
                'label'  => "Date de l'entraînement"
            ])
            ->add('start_time', TimeType::class, [
                'widget' => 'single_text',
                 'label' => "heure de début de l'entraînement",
                'required' => false,
            ])
            ->add('end_time', TimeType::class, [
                'widget' => 'single_text',
                'label' => "heure de fin de l'entraînement",
                'required' => false,
            ])
            ->add('theme', EnumType::class, [
            'class' => TrainingTheme::class,
            'choice_label' => fn (TrainingTheme $choice) => $choice->value,
            'label' => "Théme de l'entraînement",
            'placeholder' => 'Sélectionner un théme',
            'required' => false,
            ])
            ->add('coach_observation', TextareaType::class, [
                'label'=> "Observations du coach",
                'required' => false,
                'attr' => [
                'rows' => 10, 
                'placeholder' => 'Ex: Bonne intensité en 1ère mi-temps, manque de réalisme devant le but...',
            ],
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
            'data_class' => Training::class,
        ]);
    }
}
