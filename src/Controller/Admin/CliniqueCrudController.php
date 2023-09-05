<?php

namespace App\Controller\Admin;

use App\Entity\Clinique;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CliniqueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Clinique::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
