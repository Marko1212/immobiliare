<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            'email', ArrayField::new ('roles'),
        ];
    }

    public function configureActions(Actions $actions) : Actions
    {
        //cette ligne permet de 'cacher' le fait de pouvoir de créer un User dans la BDD
        //il y a aussi une commande (disable) pour rendre impossible la création
        //là, de toute façon, il ne pourra pas le faire, parce qu'on a enlevé le password
        //du template, et sans fournir un pwd, on ne peut pas créer un nouveau User
        return $actions->remove(Crud::PAGE_INDEX, Action::NEW);
    }
}
