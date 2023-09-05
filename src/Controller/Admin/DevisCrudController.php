<?php

namespace App\Controller\Admin;

use App\Entity\Devis;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class DevisCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Devis::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setDefaultSort(['id' => 'DESC'])
            ->setEntityLabelInPlural('Gérer les Devis');
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Info patient')->addCssClass('col-md-12')->setIcon('fa fa-user'),
            IdField::new('id')->setFormTypeOption('disabled', 'disabled'),

            AssociationField::new('patient')->formatValue(static function ($value, Devis $devis): ?string {
                if (!$patient = $devis->getPatient()) {
                    return null;
                }
                return sprintf('%s<br>Email: %s<br>Tel: %s<br>pays: %s', $patient->getNom(), $patient->getEmail(), $patient->getTelephine(), $patient->getPays());
            })->setFormTypeOption('disabled', 'disabled'),
            ChoiceField::new('etat')->setChoices([
                'En attente' => 'En attente',
                'A facturer' => 'A facturer',
                'Facturé' => 'Facturé',
                'Refusé' => 'Refusé',
            ]),
            FormField::addPanel('Info Devis')->addCssClass('col-md-6')->setIcon('fa fa-user'),
            AssociationField::new('operation'),
            DateTimeField::new('dateCreation')->setFormTypeOption('disabled', 'disabled'),
            DateTimeField::new('dateIntervention')->setFormTypeOption('disabled', 'disabled'),
            TextEditorField::new('message'),
            FormField::addPanel('Info supplémentaire')->addCssClass('col-md-6')->setIcon('fa fa-user'),
            TextEditorField::new('antecedentsMedicaux'),
            TextEditorField::new('antecedentsChirurgicaux'),
            TextEditorField::new('traitementEnCours'),

        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('patient')
            ->add(ChoiceFilter::new('etat')
                ->setChoices([
                    'En attente' => 'En attente',
                    'A facturer' => 'A facturer',
                    'Facturé' => 'Facturé',
                    'Refusé' => 'Refusé'])
            );
    }
}
