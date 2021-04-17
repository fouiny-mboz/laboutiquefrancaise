<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Quel nom souhaitez vous donnez à votre adresse ?',
                'attr' =>[
                    'placeholder'=>'Nomez votre adresse'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'attr' =>[
                    'placeholder'=>'Entrez votre nom'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Votre prenom',
                'attr' =>[
                    'placeholder'=>'Entrez votre prenom'
                ]
            ])
            ->add('company', TextType::class,[
                'label' => 'Votre société',
                'required'=>false,
                'attr' =>[
                    'placeholder'=>'(Facultatif) Entrez le nom de votre société'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Votre adresse',
                'attr' =>[
                    'placeholder'=>'Ajouter votre adresse'
                ]
            ])
            ->add('postal', TextType::class,[
                'label' => 'Votre code posal',
                'attr' =>[
                    'placeholder'=>'Entrez votre code postal'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Votre Ville',
                'attr' =>[
                    'placeholder'=>'Entrez votre ville'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Votre pays',
                'attr' =>[
                    'placeholder'=>'Entrez votre pays'
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Votre Téléhone',
                'attr' =>[
                    'placeholder'=>'Entrez votre Téléphone'
                ]
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Ajouter mon adresse',
                'attr' =>[
                    'class' => 'btn-block btn btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
