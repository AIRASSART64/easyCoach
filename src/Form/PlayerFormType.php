<?php

namespace App\Form;

use App\Entity\Player;
use App\Entity\User;
use App\Enum\PlayerPosition;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PlayerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name' , TextType::class, [
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
            ->add('firstname', TextType::class, [
                'label'=> 'Prénom',
                'required' => true,
                'attr'=> [
                    'placeholder'=>'Renseigner un prénom',
                    'maxlenght'=>255
                ],
                'constraints'=> [
                    new NotBlank( message:"Le prénom est requis"),
                    new Length(min:3, max:255)
                ]
            ])
            ->add('birth_date', null, [
                'widget' => 'single_text',
                'required'=>true
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse Email',
                'attr' => ['placeholder' => 'exemple@domaine.fr']
            ])
            ->add('phone', TelType::class , [
                'label'=> 'votre numéro de télephone',
                'attr' => [
        'placeholder' => '06 00 00 00 00',
                        ],
            ])
            ->add('position', EnumType::class, [
            'class' => PlayerPosition::class,
            'choice_label' => fn (PlayerPosition $choice) => $choice->value,
            'label' => 'Poste du joueur',
            'placeholder' => 'Sélectionner un poste...',
            'required' => false,
            ])
            ->add('responsable_phone', TelType::class , [
                'label'=> 'Téléphone du responsable légal',
                'attr' => [
                'placeholder' => '06 00 00 00 00',
            ],
            ])
            ->add('responsable_email', EmailType::class, [
                'label' => 'Email du responsable légal',
                'attr' => ['placeholder' => 'exemple@domaine.fr']
            ])
            ->add('coach', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
}
