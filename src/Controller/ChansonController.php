<?php

namespace App\Controller;

use App\Entity\Chanson;
use App\Form\ChansonType;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Provider\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use function Zenstruck\Foundry\factory;
use function Zenstruck\Foundry\faker;

class ChansonController extends AbstractController
{
    /**
     * @Route("/chanson", name="app_chanson")
     */
    public function index(): Response
    {
        return $this->render('chanson/index.html.twig', [
            'controller_name' => 'ChansonController',
        ]);
    }

    //Fonction permettant de lister toutes les chansons depuis la base de données
    /**
     * @Route("/touteschansons", name="touteschansons")
     */
    public function touteschansons(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Chanson::class);
        $listeChansons = $repository->findAll();

        return $this->render('chanson/toutes.html.twig', [
            'chansons' => $listeChansons,
        ]);
    }

    //Fonction permettant d'afficher les détails d'une chanson
    /**
     * @Route("/detailchanson/{id}", name="detailchanson")
     */
    public function detailchanson($id, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Chanson::class);
        $chanson = $repository->find($id);

        return $this->render('chanson/detail.html.twig', [
            'chanson' => $chanson
    ]);
    }




    //Fonction permettant de générer aléatoirement des chansons dans la base de données
    /**
     * @Route("/nouvellechansonaleatoire", name="nouvellechansonaleatoire")
     */
    public function nouvellechanson(EntityManagerInterface $entityManager)
    {
        $chanson = new chanson();
        $faker = Factory::create();
        $titre = $faker->words(2, true);
        $album = $faker->words(3, true);
        $paroles = $faker->paragraph(6);
        $auteur = $faker->name();
        $dateajout = $faker->dateTimeBetween('now','now');
        $datesortie = $faker->dateTimeBetween('-40years','now');
        $chanson->setTitre($titre);
        $chanson->setAlbum($album);
        $chanson->setParoles($paroles);
        $chanson->setAuteur($auteur);
        $chanson->setDateAjout($dateajout);
        $chanson->setDateSortie($datesortie);

        $entityManager->persist($chanson);
        $entityManager->flush();

        return $this->render('chanson/confirmation.html.twig', [
            'controller_name' => 'ChansonController',
        ]);

    }

    /**
     * @Route("/ajouterchanson", name="ajouterchanson")
     */

    public function ajouterchanson(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chanson = new Chanson();
        $chanson->setDateAjout(new \DateTime('now'));
        $form = $this->createForm(ChansonType::class, $chanson);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $chanson = $form->getData();
            $entityManager->persist($chanson);
            $entityManager->flush();

            return $this->redirectToRoute('detailchanson');
        }

        return $this->renderForm('chanson/ajouter.html.twig', [
            'form'=>$form
        ]);
    }

    /**
     * @Route("/detailchanson/{id}/supprimer", name="supprimerchanson")
     */

    public function supprimerChanson(int $id, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Chanson::class);
        $chanson = $repository->find($id);
        $entityManager->remove($chanson);
        $entityManager->flush();

        return $this->redirectToRoute('touteschansons');
    }

    /**
     * @Route("/detailchanson/{id}/modifier", name="modifierchanson")
     */

    public function modifierChanson(int $id, EntityManagerInterface $entityManager, Request $request)
    {
        $repository = $entityManager->getRepository(Chanson::class);
        $chanson = $repository->find($id);
        $form = $this->createForm(ChansonType::class, $chanson);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $chanson = $form->getData();
            $entityManager->flush();

            return $this->redirectToRoute('touteschansons');
        }

        return $this->renderForm('chanson/modifier.html.twig', [
            'form'=>$form,
            'chanson'=>$chanson,
        ]);
    }
}
