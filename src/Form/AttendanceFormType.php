<?php

namespace App\Form;

use App\Entity\Attendance;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Status;
use App\Entity\Training;
use App\Repository\GameRepository;
use App\Repository\PlayerRepository;
use App\Repository\TrainingRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttendanceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $coach = $options['coach'];
        $builder
           
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable', 
                'label'  => "Début de l'absence",
                'required'=> true 
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'label'  => "Fin de l'absence",
                'required'=> false
            ])
            ->add('justificationDate', DateType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'label'  => "Justifié le",
                'required'=> false
            ])
            ->add('coach_observation', TextareaType::class, [
                'label'=> "Observations du coach",
                'required' => false,
                'attr' => [
                    'rows' => 7, 
                    'placeholder' => 'Raison de l\'absence, certificat médical...',
                    'class' => 'bg-slate-50 border-2 border-slate-200 p-3 w-full focus:bg-white focus:border-indigo-500 transition-all'
                ],
            ])
          
            ->add('player', EntityType::class, [
                'class' => Player::class,
                'query_builder' => function (PlayerRepository $er) use ($coach) {
                    return $er->createQueryBuilder('p')
                        ->andWhere('p.coach = :coach')
                        ->setParameter('coach', $coach)
                        ->orderBy('p.name', 'ASC');
                },
                'choice_label' => function (Player $player) {
                    return $player->getFirstName() . ' ' . $player->getName();
                },
                'label' => 'Joueur concerné',
                'placeholder' => 'Sélectionner un joueur'
            ])
       
            ->add('game', EntityType::class, [
                'class' => Game::class,
                'query_builder' => function (GameRepository $er) use ($coach) {
                    return $er->createQueryBuilder('g')
                        ->andWhere('g.coach = :coach')
                        ->setParameter('coach', $coach)
                        ->orderBy('g.date', 'DESC');
                },
                'choice_label' => function(Game $game) {
                    return $game->getDate()->format('d/m/Y') . ' vs ' . $game->getOpposingTeam();
                },
                'label' => 'Match lié (optionnel)',
                'required' => false,
                'placeholder' => 'Sélectionnez le match'
            ])
       
            ->add('training', EntityType::class, [
                'class' => Training::class,
                'query_builder' => function (TrainingRepository $er) use ($coach) {
                    return $er->createQueryBuilder('t')
                        ->andWhere('t.coach = :coach')
                        ->setParameter('coach', $coach)
                        ->orderBy('t.date', 'DESC');
                },
                'choice_label' => function(Training $training) {
                    $date = $training->getDate() ? $training->getDate()->format('d/m/Y') : 'Date inconnue';
                    $theme = $training->getTheme() ? $training->getTheme()->value : 'Sans thème';
                    return $date . ' - ' . $theme; 
                },
                'label' => "Entraînement lié (optionnel)",
                'required' => false,
                'placeholder' => "Sélectionnez la séance d'entraînement"
            ])
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'name',
                'label'=> "Statut (Présent, Blessé, Absent...)"
            ]);
        //     ->add('coach', EntityType::class, [
        //         'class' => User::class,
        //          'choice_label' => function (User $user) {
        //         return $user->getFirstName() . ' ' . $user->getName();
        //         },
        //         'label' => 'Coach référent'
        //     ])
        // ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Attendance::class,
        ]);
    }
}