<?php

namespace App\Form;

use App\Entity\Equipment;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EquipmentFormType extends AbstractType
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
            ->add('total_quantity' , IntegerType::class, [
                'label' => "Quantité total",
                'required' => true,
                'attr' => [
                 'min' => 0,                         
                'step' => 1,             
                'placeholder' => '-',    
                'inputmode' => 'numeric', 
           
        ]])
            ->add('alert_level', IntegerType::class, [
                'label' => "Quantité minimale requise ",
                'required' => true,
                'attr' => [
                 'min' => 0,                         
                'step' => 1,             
                'placeholder' => '-',    
                'inputmode' => 'numeric', 
           
        ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipment::class,
            'coach' => null, 
        ]);
        $resolver->setAllowedTypes('coach', [User::class, 'null']);
        
    }
}
