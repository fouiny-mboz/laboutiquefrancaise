<?php

namespace App\Controller\Admin;

use App\Entity\Header;
Use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
Use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
Use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class HeaderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre du header'),
            TextareaField::new('content', 'Contenu de notre header'),
            TextField::new('btnTitle', 'Titre de notre boutton'),
            TextField::new('btnUrl', 'Url de destination de notre boutton'),
            ImageField::new('illustration')
                ->setBasePath('uploads')
                ->setUploadDir('public/uploads/')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            
        ];
    }
    
}
