<?php

namespace App\Form;

use App\Entity\Equipment;
use App\Entity\Stock;
use App\Entity\User;
use App\Enum\TypeStockManagment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

              ->add('equipment', EntityType::class, [
                'class' => Equipment::class,
                'choice_label' => 'name',
                'label' => 'Equipement',
                'placeholder' => 'Choisir un équipement',   
            ])

            ->add('quantity' , IntegerType::class, [
                'label' => "Quantité",
                'required' => true,
                'attr' => [
                 'min' => 0,                         
                'step' => 1,             
                'placeholder' => '-',    
                'inputmode' => 'numeric', 
           
        ]])
            ->add('type',EnumType::class, [
            'class' => TypeStockManagment::class,
            'choice_label' => fn (TypeStockManagment $choice) => $choice->value,
            'label' => "Motif",
            'placeholder' => 'Sélectionner un motif ',
            'required' => true,
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'label'  => 'Date',
                'required' => true,

            ])
            ->add('reason', TextareaType::class, [
                'label'=> "Observation",
                'required' => false,
                'attr' => [
                'rows' => 10, 
                'placeholder' => "tous les compélments d'information ",
            ],
            ])
          
            ->add('coach', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                return $user->getFirstName() . ' ' . $user->getName();
                },
                'label' => 'Coach référent',
            
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stock::class,
        ]);
    }
}
