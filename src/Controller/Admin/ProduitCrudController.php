<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('titre', 'titre'),
            ImageField::new('photo')->setBasePath('images/produit/')->setUploadedFileNamePattern('[ulid].[extension]')->setUploadDir('public/images/produits')->setRequired(false),
            TextareaField::new('couleur', 'couleur'),
            TextareaField::new('taille', 'taille'),

             //DateTimeField::new('dateEnregistrement', "date enregistrement")->setFormat("d/M/Y à H:m:s"),
            // AssociationField::new('taille', 'taille'),
            TextEditorField::new('description', 'description'),
            TextField::new('collection', 'collection'),
             NumberField::new('prix')->setRoundingMode(\NumberFormatter::ROUND_CEILING),
             NumberField::new('stock', 'stock'),
             Datetimefield::new('dateEnregistrement', 'Date d enregistrement')->onlyOnForms("d/M/Y à H:m:s"),
            //  Datetimefield::new('updatedAt', 'Date de mise à jourt')->onlyOnForms("d/M/Y à H:m:s"),

        ];
    }
    

    public function createEntity(string $entityFqcn)
    {
        //createdEntity est exécuée lorsue je clique sur "add article"
        // elle permet d'exécuter du code avant d'aaficher la page du form de création
        // ici, je définis une date de création est une date de mise à jour

        $produit = new Produit;
        $produit->setDateEnregistrement(new \DateTime);
                //  ->setUpdatedAt(new \DateTime);
                return $produit;
    }
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        //updateEntity() est exécuté lors de la suomission du formulaire de mise à jour
        $ifile = $entityInstance->getImage();

        if(!$ifile)
        {
            $entityInstance->setImage('default.jpg');// image par défaut qui s'affiche on doit préciser le chemin qui doit être placer dans le dossier des images
        }

        $entityInstance->setUpdatedAt(new DateTime);
        // $entityManager->persist($entityInstance);
        $entityManager->flush();

    }
    
}
