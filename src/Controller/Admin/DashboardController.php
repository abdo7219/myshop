<?php

namespace App\Controller\Admin;
use App\Entity\User;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MYSHOP');
    }

    public function configureMenuItems(): iterable
    {
        return [
        MenuItem::linkToDashboard('Accueil', 'fa fa-home'),
        MenuItem::section('shop'), // Crée une section pour catégoriser les items
        MenuItem::linkToCrud('Produits', 'fas fa-newspaper', Produit::class),
        MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class),
];
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
