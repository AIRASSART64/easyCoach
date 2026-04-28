<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email' , EmailType::class, [
                'label' => 'Adresse Email',
                'attr' => ['placeholder' => 'exemple@domaine.fr']

            ])
            ->add('name' , TextType::class, [
                'label'=> 'Votre nom',
                'required' => true,
                'attr'=> [
                    'placeholder'=>'Renseigner votre nom',
                    'maxlenght'=>255
                ],
                'constraints'=> [
                    new NotBlank( message:"Le nom est requis"),
                    new Length(min:3, max:255)
                ]
            ])
            ->add('firstname', TextType::class, [
                 'label'=> 'Votre prénom',
                'required' => true,
                'attr'=> [
                    'placeholder'=>'Renseigner votre prénom',
                    'maxlenght'=>255
                ],
                'constraints'=> [
                    new NotBlank( message:"Le prénom est requis"),
                    new Length(min:3, max:255)
                ]

            ])
            ->add('phone', TelType::class , [
                'label'=> 'votre numéro de télephone',
                'attr' => [
        'placeholder' => '06 00 00 00 00',
    ],
            ]
            )
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue(
                        message: 'You should agree to our terms.',
                    ),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(
                        message: 'Renseigner un mot de passe',
                    ),
                    new Length(
                        min: 6,
                        minMessage: 'Votre mot de passe doir contenir au moins {{ limit }} caractéres',
                        // max length allowed by Symfony for security reasons
                        max: 4096,
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
