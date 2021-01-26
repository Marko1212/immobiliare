<?php

namespace App\Controller\Admin;

use App\Entity\RealEstate;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RealEstateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RealEstate::class;
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
