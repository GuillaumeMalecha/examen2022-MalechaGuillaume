<?php

namespace App\Controller;

use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    /**
     * @Route("/genre", name="app_genre")
     */
    public function index(): Response
    {
        return $this->render('genre/index.html.twig', [
            'controller_name' => 'GenreController',
        ]);
    }

    //Fonction permettant de lister tous les genres présents dans la base de données
    /**
     * @Route("/tousgenres", name="tousgenres")
     */
    public function tousgenres(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Genre::class);
        $listeGenres = $repository->findAll();

        return $this->render('genre/tous.html.twig', [
            'genres' => $listeGenres,
        ]);
    }


    //Fonction permettant d'ajouter un genre musical
    /**
     * @Route("/nouveaugenre", name="nouveaugenre")
     */
    public function nouveaugenre(EntityManagerInterface $entityManager)
    {
        $genre = new genre();

        //le code ci-dessous permet de générer au hasard des genre musicaux inexplorés
        //$faker = Factory::create();
        //$nom = $faker->words(2, true);
        //$description = $faker->words(3, true);

        //le code ci-dessous permet d'ajouter manuellement un genre à la base de données
        $nom = 'Rock';
        $description = "Guitares, lunettes cools et cuir";

        $genre->setNom($nom);
        $genre->setDescription($description);

        $entityManager->persist($genre);
        $entityManager->flush();

        return $this->render('genre/confirmation.html.twig', [
            'controller_name' => 'GenreController',
        ]);

    }
}
