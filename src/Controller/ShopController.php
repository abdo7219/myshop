<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShopController extends AbstractController
{
    #[Route('/', name: 'app_shop')]
    public function index(ProduitRepository $repo): Response
    {
        $produits = $repo->findAll();
        return $this->render('shop/index.html.twig', [
            'tabProduits' => $produits,
        ]);
    }


    #[Route('/shop/new', name: 'shop_create')]
    #[Route('/shop/edit/{id}', name: 'shop_edit')]

    public function form(Request $superglobals, EntityManagerInterface $manager, Produit $produit = null)
    {
        // la classe Request contient les données véhiculées par les superglobales ($_POST, $_GET...)

        //dump($superglobals);
        // si symfony ne récuper pas d'objet Article, nous en créons un cvide

        if($produit == null)    // équivalent à if(!$article)
        {
        
             $produit = new Produit;// je crée un objet Article vide prêt à être rempli
             $produit->setDateEnregistrement(new \DateTime()); // ajouter la date seulement à l'nsertion d'un article
        }

       $form = $this->createForm(ProduitType::class, $produit);// lier le formulaire à l objet
        //createForme()permet de récuperer tous les formulaire existant

        $form->handleRequest($superglobals);


        // handleRequest() permet d'insérer les données du formulaire dans l'objet $article
        // elle permet aussi de faire des vérifs sur formulaires(quelle est la méthode? est ce que les champs sont tous rempli ? etc)
        
        //dump($produit);

if($form->isSubmitted() && $form->isValid())
{
    
    $manager->persist($produit); //prépare la future requête
    $manager->flush(); // exécute la requête(insertion)
return $this->redirectToRoute('shop_show', [
    'id' => $produit->getId()
]);
//cette méthode permet de redireger vers la page de notre article nouvellement crée
}

        return $this->renderForm("shop/form.html.twig",[
            'formProduit' => $form,
            'editMode' => $produit->getId() !== NULL
        ]);

        
    }
    #[Route('/shop/delete/{id}', name:'shop_delete')]
// prépare la rout de suppression
    public function delete(EntityManagerInterface $manager, $id, ProduitRepository $repo)
    {
$produit = $repo->find($id);

$manager->remove($produit);
//remove prépare la supression d'un article

$manager->flush();
//exécute la requête préparée(suppression)

$this->addFlash('success', "l'produit a bien été suprimé !");

//addFlash() permet de créer un msg de notification
//Il prend 2 argument  le 1er type de message(ce que l'un veut, pas de type prédéfini)
// le 2 eme arg est le message

return $this->redirectToRoute(("app_shop"));
//redirection vers la liste des produit après la suppression
//nous afficherons le message Flash sur le template affiché sur la route app_blog(index.html.twig)
    }

    #[Route('/shop/show/{id}', name: 'shop_show')]

    public function show($id, ProduitRepository $repo): Response

    {
        $produit = $repo->find($id);

        return $this->render('shop/show.html.twig', [
            'produit' => $produit
        ]);
    }
}
