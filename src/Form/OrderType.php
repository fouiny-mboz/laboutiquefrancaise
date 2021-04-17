<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $builder
            ->add('adresses', EntityType::class,[
                'label' =>false,
                'required' => true,
                'class'=> Address::class,
                'choices' => $user->getAdresses(),
                'multiple' => false,
                'expanded' => true,

            ])
            ->add('carriers', EntityType::class,[
                'label' =>'Choisissez votre livreur',
                'required' => true,
                'class'=> Carrier::class,
                'multiple' => false,
                'expanded' => true,

            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider ma commande',
                'attr'=> [
                    'class' => 'btn btn-success btn-block'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'user' => array()
        ]);
    }
}
