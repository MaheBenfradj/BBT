<?php

namespace App\Form;

use App\Entity\Clinique;
use App\Entity\DemandeRDV;
use App\Entity\Intervention;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Countries;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeRDV1Type extends AbstractType
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
            ->add('intervention', EntityType::class, array(
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('i')
                            ->where('i.afficher = true')
                            ->orderBy('i.nom', 'ASC');
                    },
                    'class' => Intervention::class,
                    'attr' => array(
                        'id' => "operation",
                        'class' => "form-select",

                    ),
                    'placeholder' => 'Opérations Souhaitées',
                    'label' => 'Opération')
            )
            ->add('clinique', EntityType::class, array(
                    'required' => false,
                    'class' => Clinique::class,
                    'attr' => array(
                        'id' => "clinique",
                        'class' => "form-select",
                    ),
                    'placeholder' => 'Cliniques Souhaitées',
                )
            )
            ->add('nom', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom / Prénom',
                    'id' => "name",
                    'data-msg' => "Please enter at least 4 chars",
                    ' data-rule' => "minlen:4",
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'name' => 'email',
                    'id' => 'email',
                    'data-rule' => 'email',
                    'placeholder' => 'Email',
                    'data-msg' => 'Please enter a valid email'
                ]
            ])
            ->add('telephone', NumberType::class, ['label' => 'Numéro de téléphone',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'name' => "phone",
                    'id' => "phone",
                    'placeholder' => 'Numéro de téléphone',
                    'min' => 1000000000,
                    'max' => 9999999999,
                    'data-rule' => "minlen:4",
                    'data-msg' => "Please enter at least 4 chars"
                ],
            ])
            ->add('dateRDV', DateType::class, [
                'label' => 'Date RDV',
                'widget' => 'single_text',
                // 'html5' => false,
                'placeholder' => "Date de rendez-vous jj/mm/aaaa",


                'attr' => [
                    'name' => "date", '
                    class' => "form-control datepicker",
                    'id' => "date",
                    'placeholder' => "Date de rendez-vous (format:jj/mm/aaaa)",

                ],

            ])
            ->add('message', TextareaType::class, array(
                    'attr' => array(
                        'id' => "message",
                        'class' => "form-control",
                        'placeholder' => "Message (Optional) ",
                        'aria-label' => "Souhaitez-vous ajouter autre chose? ",
                        'aria-describedby' => "basic-icon-default-message2",
                        'name' => "message",
                        'rows' => "5",
                        'required' => false,
                    ),
                )
            )
            ->add('dateNaissance', null, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Age',
                ],

            ])
            ->add('pays', ChoiceType::class, [
                'choices' => array_flip($countries),
                'required' => true,
                'placeholder' => 'Sélectionnez votre pays',
                'attr' => [
                    'class' => 'form-control',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DemandeRDV::class,
        ]);
    }
}
