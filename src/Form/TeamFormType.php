<?php

namespace App\Form;


use App\Entity\Team;
use App\Entity\TeamCategory;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TeamFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'=> 'Nom',
                'required' => true,
                'attr'=> [
                    'placeholder'=>'Renseigner un nom',
                    'maxlenght'=>255
                ],
                'constraints'=> [
                    new NotBlank( message:"Le nom est requis"),
                    new Length(min:3, max:255)
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => TeamCategory::class,
                'choice_label' => 'name',
                'label'=>'Catégorie'
            ])
            ->add('coach', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'name',
            ])
            // ->add('player', EntityType::class, [
            //     'class' => Player::class,
            //     'choice_label' => function(Player $player) {
            //     return $player->getFirstname() . ' ' . $player->getName();
            //     },
            //     'multiple' => true,    
            //      'expanded' => true,    
            //     'label' => 'Sélectionner les joueurs',
            //     'by_reference' => false,
            //     ])
                ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}
