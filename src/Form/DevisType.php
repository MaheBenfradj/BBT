<?php

namespace App\Form;

use App\Entity\Devis;
use App\Entity\Intervention;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void

    {
        $builder
            ->add('patient', PatientType::class, array(
                    'label' => '  '
                )
            )
            ->add('dateCreation', HiddenType::class, array(
                    'attr' => array(
                        'id' => "dateC",
                        'class' => "form-control",
                        'hidden' => true
                    ),

                )
            )
            ->add('dateIntervention', DateType::class, [
                'label' => 'Date d\'intrvention',
                'widget' => 'single_text',

                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'JJ-MM-AAAA',
                ],

            ])
            ->add('message', TextareaType::class, array(
                    'attr' => array(
                        'id' => "message",
                        'class' => "form-control",
                        'placeholder' => "Souhaitez-vous ajouter autre chose? ",
                        'aria-label' => "Souhaitez-vous ajouter autre chose? ",
                        'aria-describedby' => "basic-icon-default-message2"
                    ),
                    'label' => 'Message')
            )
            ->add('AntecedentsMedicaux', TextareaType::class, array(
                    'attr' => array(
                        'id' => "AntecedentsMedicaux",
                        'class' => "form-control",
                        'placeholder' => "Avez-vous des antécédents Médicaux? ",
                        'aria-label' => "Avez-vous des antécédents Médicaux? ",
                        'aria-describedby' => "basic-icon-default-AntecedentsMedicaux2"
                    ),
                    'label' => 'Antécédents Médicaux')
            )
            ->add('antecedentsChirurgicaux', TextareaType::class, array(
                    'attr' => array(
                        'id' => "antecedentsChirurgicaux",
                        'class' => "form-control",
                        'placeholder' => "Avez-vous des antécédents Chirurgicaux? ",
                        'aria-label' => "Avez-vous des antécédents Chirurgicaux? ",
                        'aria-describedby' => "basic-icon-default-antecedentsChirurgicaux2"
                    ),
                    'label' => 'Antécédents Chirurgicaux')
            )
            ->add('traitementEnCours', TextareaType::class, array(
                    'attr' => array(
                        'id' => "traitementEnCours",
                        'class' => "form-control",
                        'placeholder' => "Traitement En Cours? ",
                        'aria-label' => "Traitement En Cours? ",
                        'aria-describedby' => "basic-icon-default-traitementEnCours2"
                    ),
                    'label' => 'Traitement En Cours')
            )
            ->add('operation', EntityType::class, array(
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('i')
                            ->where('i.afficher = true')
                            ->orderBy('i.nom', 'ASC');
                    },
                    'class' => Intervention::class,
                    'attr' => array(
                        'id' => "operation",
                        'class' => "form-control",

                    ),
                    'placeholder' => 'Opérations Souhaitées',
                    'label' => 'Opération')
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,

        ]);
    }
}
