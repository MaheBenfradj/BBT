<?php

namespace App\Controller\Admin;

use App\Entity\Parametre;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ParametreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Parametre::class;
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
