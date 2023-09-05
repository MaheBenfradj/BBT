<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $countryCodes = Countries::getNames();
        asort($countryCodes); // Sort the country codes by ascending alphabetical order

        $countries = [];

        foreach ($countryCodes as $code => $name) {
            $countries[$name] = $name;
        }

        $builder
            ->add('nom', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom / Prénom',
                ]
            ])
            ->add('dateNaissance', BirthdayType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'required' => true,

                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'JJ-MM-AAAA',
                ],

            ])
            ->add('pays',ChoiceType::class,[
                'choices' => array_flip($countries),
                'required' => true,
                'placeholder' => 'Sélectionnez un pays',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('adresse',null,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Adresse',
                ]
            ])
            ->add('email', EmailType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Email',
                ]
            ])
            ->add('dateCreation', HiddenType::class,)
            ->add('telephine', NumberType::class, ['label' => 'Numéro de téléphone',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Numéro de téléphone',
                    'min' => 1000000000,
                    'max' => 9999999999,
                ],
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
