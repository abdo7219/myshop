<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{

    private $hasher;
    public function _construct(UserPasswordHasherInterface $hasher)
    {
$this->hasher = $hasher;

    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('email'),
            TextField::new('nom', 'Nom '),
            TextField::new('prenom', 'Prenom'),
            TextField::new('password', 'Mot de passe')->setFormType(PasswordType::class)->onlyOnForms()->onlyWhenCreating(),
            CollectionField::new('roles')->setTemplatePath('admin/field/roles.html.twig'),
        ];
    }
    

public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
{
    if (!$entityInstance->getId()) {
        $entityInstance->setPassword(
            $this->hasher->hashPassword($entityInstance, $entityInstance->getPassword())
        );
    }
    $entityManager->persist($entityInstance);
    $entityManager->flush();
}


}
