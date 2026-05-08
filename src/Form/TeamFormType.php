<?php

namespace App\Form;


use App\Entity\Team;
use App\Entity\TeamCategory;
use App\Entity\User;
use App\Repository\TeamCategoryRepository;
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
        $coach = $options['coach'];
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
                'label'=>'Catégorie',
                'query_builder' => function (TeamCategoryRepository $er) use ($coach) {
                    return $er->createQueryBuilder('c')
                        ->andWhere('c.coach = :coach')
                        ->setParameter('coach', $coach)
                        ->orderBy('c.name', 'ASC');
             },]);
            // ->add('coach', EntityType::class, [
            //     'class' => User::class,
            //       'choice_label' => function (User $user) {
            //     return $user->getFirstName() . ' ' . $user->getName();
            //     },
            //     'label' => 'Coach référent'
            // ])
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
            'coach' => null,
        ]);
        $resolver->setAllowedTypes('coach', [User::class, 'null']);
    }
}
