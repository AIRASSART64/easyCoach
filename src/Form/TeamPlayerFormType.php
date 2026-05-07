<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\Player;
use App\Entity\User;
use App\Repository\PlayerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamPlayerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $coach = $options['coach'];

        $builder
            ->add('player', EntityType::class, [ 
                'class' => Player::class,
                'multiple' => true,      
                'expanded' => true,     
                'label' => 'Choisir les joueurs',
                'choice_label' => function(Player $player) {
                    return $player->getFirstName() . ' ' . $player->getName();
                },
          
                'query_builder' => function (PlayerRepository $er) use ($coach) {
                    return $er->createQueryBuilder('p')
                        ->andWhere('p.coach = :coach')
                        ->setParameter('coach', $coach)
                        ->orderBy('p.name', 'ASC');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
            'coach' => null,
        ]);

        $resolver->setAllowedTypes('coach', [User::class, 'null']);
    }
}