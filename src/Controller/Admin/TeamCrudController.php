<?php

namespace App\Controller\Admin;

use App\Entity\Team;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TeamCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Team::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('photo')->setUploadDir('public/bilder'),
            TextField::new('name'),
            TextField::new('jobtitle'),
            TextField::new('facebook'),
            TextField::new('twitter'),
            TextField::new('instagram')
        ];
    }

}
