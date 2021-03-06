<?php

namespace App\Controller\Admin;

use App\Entity\OurService;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OurServiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OurService::class;
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
