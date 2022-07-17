<?php

namespace App\Controller;

use App\Entity\Film;
use App\Form\FilmType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FilmController extends AbstractController
{
    /**
     * @Route("/film", name="app_film")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $films =$doctrine->getRepository(Film::class)->findAll();
        return $this->render('film/index.html.twig', [
            'films' => $films,
        ]);
    }

    /**
    * @Route("/film/add", name="add_film")
    * @Route("/film/{id}edit", name="edit_film")
    */
    // on relie la bdd , on dit quel objet on veut cree ou modif , et on fait la requete http
    public function add(ManagerRegistry $doctrine, Film $film = null, Request $request): Response
    {
        // si le film existe pas on crée un nouvelle objet sinon on modifie 
        if(!$film){
            $film =new Film();

        }
        // crée le formulaire de type film 
        $form = $this->createForm(FilmType::class,$film);
        $form->handleRequest($request);
        // si envoye et sanitise avec les filter etc protection faille xss puis on execute le tout 
        if($form->isSubmitted() && $form->isValid())
        {
            $afficheFile = $form->get('affiche')->getData();
            if ($afficheFile) {
                $newFilename = 'img_affiche/'.uniqid().'.'.$afficheFile->guessExtension();
                try {
                    $afficheFile->move(
                        $this->getParameter('img_affiche_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $film->setAffiche($newFilename);
            }

            $film = $form->getData();
            $entityManager = $doctrine->getManager();
            // hydrate et protection faille sql 
            $entityManager->persist($film);
            $entityManager->flush();

            $this->addFlash("success" , $film->getTitreFilm()." à été ajouté/Modifié avec succès");

            return $this->redirectToRoute('app_film'); 
        }
        return $this->render('film/add.html.twig', [
            'formAddFilm' =>  $form->createView(),
            'edit' => $film->getId(),
        ]);
    }
    
    /**
    * @Route("/film/{id}delete", name="delete_film")
    */
    public function delete(ManagerRegistry $doctrine, Film $film ) :Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($film);
        $entityManager->flush();
        $this->addFlash("success" , $film->getTitreFilm()." à été supprimé avec succès");

        return $this->redirectToRoute("app_film");
    }

    
    /**
     * @Route("/film/{id}", name="detail_film")
     */
    public function detail(Film $film): Response
    {
        return $this->render('film/detail.html.twig', [
            'film' => $film,
        ]);
    }



}
