<?php

namespace App\Controller\Admin;

use App\Entity\Downloads;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DownloadsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Downloads::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('size'),
            ImageField::new('link')->setUploadDir('public/downloads'),
        ];
    }

}
