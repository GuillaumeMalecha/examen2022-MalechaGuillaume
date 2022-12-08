<?php

namespace App\Controller;

use App\Entity\Chanson;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Provider\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
}
