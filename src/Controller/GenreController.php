<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Genre;
use App\Form\GenreType;
use Doctrine\ORM\Mapping\Id;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GenreController extends AbstractController
{
    /**
     * @Route("/genre", name="app_genre")
     */
    public function index(ManagerRegistry $doctrine ): Response
    {
        $genres = $doctrine->getRepository(Genre::class)->findAll();
        return $this->render('genre/index.html.twig', [
            'genres' => $genres,
        ]);
    }

        
    /**
    * @Route("/genre/add", name="add_genre")
    * @Route("/genre/{id}edit", name="edit_genre")
    */
    // on relie la bdd , on dit quel objet on veut cree ou modif , et on fait la requete http
    public function add(ManagerRegistry $doctrine, Genre $genre = null, Request $request): Response
    {
        // si le genre existe pas on crée un nouvelle objet sinon on modifie 
        if(!$genre){
            $genre =new Genre();
    
        }
        // crée le formulaire de type genre 
        $form = $this->createForm(GenreType::class,$genre);
        $form->handleRequest($request);
        // si envoye et sanitise avec les filter etc protection faille xss puis on execute le tout 
        if($form->isSubmitted() && $form->isValid())
        {
            $genre = $form->getData();
            $entityManager = $doctrine->getManager();
            // hydrate et protection faille sql 
            $entityManager->persist($genre);
            $entityManager->flush();

            $this->addFlash("success" , $genre->getLibelle()." à été ajouté avec succès");

            return $this->redirectToRoute('app_genre'); 
        }
        return $this->render('genre/add.html.twig', [
            'formAddGenre' =>  $form->createView(),
            'edit' => $genre->getId(),
        ]);
    }
    
    /**
    * @Route("/genre/{id}delete", name="delete_genre")
    */
    public function delete(ManagerRegistry $doctrine, Genre $genre ) :Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($genre);
        $entityManager->flush();
        $this->addFlash("success" , $genre->getLibelle()." à été supprimé avec succès");
        return $this->redirectToRoute("app_genre");
    }
    
    /**
     * @Route("/genre/{id}", name="detail_genre")
     */
    public function detail(ManagerRegistry $doctrine, Genre $genre )
    {
        return $this->render('genre/detail.html.twig', [
            'genre' => $genre,
        ]);
    }

    
}
