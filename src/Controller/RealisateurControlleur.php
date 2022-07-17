<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Realisateur;
use App\Form\RealisateurType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class RealisateurControlleur extends AbstractController
{
    /**
    * @Route("/realisateur", name="app_realisateur")
    */
    public function index(ManagerRegistry $doctrine): Response
    {
        $realisateurs = $doctrine->getRepository(Realisateur::class)->findAll();
        return $this->render('realisateur/index.html.twig', [
            'realisateurs' => $realisateurs,
        ]);
    }
    
    /**
    * @Route("/realisateur/add", name="add_realisateur")
    * @Route("/realisateur/{id}edit", name="edit_realisateur")
    */
    // on relie la bdd , on dit quel objet on veut cree ou modif , et on fait la requete http
    public function add(ManagerRegistry $doctrine, Realisateur $realisateur = null, Request $request,SluggerInterface $slugger): Response
    {
        // si le realisateur existe pas on crée un nouvelle objet sinon on modifie 
        if(!$realisateur){
            $realisateur =new Realisateur();
    
        }
        // crée le formulaire de type realisateur 
        $form = $this->createForm(RealisateurType::class,$realisateur);
        $form->handleRequest($request);
        // si envoye et sanitise avec les filter etc protection faille xss puis on execute le tout 
        if($form->isSubmitted() && $form->isValid())
        {
            $imageRealisateurFile = $form->get('imageRealisateur')->getData();
            if ($imageRealisateurFile) {
                $originalFilename = pathinfo($imageRealisateurFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = 'img_affiche/'.uniqid().'.'.$imageRealisateurFile->guessExtension();
                try {
                    $imageRealisateurFile->move(
                        $this->getParameter('imageRealisateur_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $realisateur->setImageRealisateur($newFilename);
            }

            $realisateur = $form->getData();
            $entityManager = $doctrine->getManager();
            // hydrate et protection faille sql 
            $entityManager->persist($realisateur);
            $entityManager->flush();
            $this->addFlash("success" , $realisateur->getRealisateur()." à été ajouté avec succès");
            return $this->redirectToRoute('app_realisateur'); 
        }
        return $this->render('realisateur/add.html.twig', [
            'formAddRealisateur' =>  $form->createView(),
            'edit' => $realisateur->getId(),
        ]);
    }
    
    /**
    * @Route("/realisateur/{id}delete", name="delete_realisateur")
    */
    public function delete(ManagerRegistry $doctrine, Realisateur $realisateur ) :Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($realisateur);
        $entityManager->flush();
        $this->addFlash("success" , $realisateur->getNom()." ".$realisateur->getPrenom()." à été supprimé avec succès");

        return $this->redirectToRoute("app_realisateur");
    }

    /**
    * @Route("/realisateur/{id}", name="detail_realisateur")
    */
    public function detail( Realisateur $realisateur): Response
    {
        return $this->render('realisateur/detail.html.twig', [
            'realisateur' => $realisateur,
        ]);
    }

    
}
