<?php

namespace App\Form;

use App\Entity\TeamCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TeamCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class , [
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TeamCategory::class,
        ]);
    }
}
