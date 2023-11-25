<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Form\ClubType;
use App\Repository\ClubRepository;
use App\Repository\EquipeRepository;
use App\Repository\JoueurRepository;
use App\Service\ClubService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ClubController extends AbstractController
{
    #[Route('/club', name: 'app_club')]
    public function index(
        EquipeRepository $equipeRepository,
        JoueurRepository $joueurRepository
    ): Response {
        $allJoueurs = $joueurRepository->findAll();
        $nbEquipe = count($equipeRepository->findAll());
        $nbJoueurs = count($allJoueurs);
        $nbCompetiteur = count(array_filter($allJoueurs, function ($joueur) {
            return $joueur->getTypeLicence() === Joueur::TYPELICENCE['Compétition'];
        }));
        $nbLoisir = count(array_filter($allJoueurs, function ($joueur) {
            return $joueur->getTypeLicence() === Joueur::TYPELICENCE['Loisir'];
        }));
        $nbEvenementiel = count(array_filter($allJoueurs, function ($joueur) {
            return $joueur->getTypeLicence() === Joueur::TYPELICENCE['Evénementiel'];
        }));

        return $this->render('club/index.html.twig', [
            'nbJoueurs' => $nbJoueurs,
            'nbEquipe' => $nbEquipe,
            'nbCompetiteur' => $nbCompetiteur,
            'nbLoisir' => $nbLoisir,
            'nbEvenementiel' => $nbEvenementiel,
        ]);
    }

    #[Route('/club/edit', name: 'app_club_edit')]
    public function edit(
        ClubRepository $clubRepository,
        ClubService $clubService,
        Request $request,
        SluggerInterface $slugger
    ): Response {
        $club = $clubService->getClub();
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
                $club->setImage($newFilename);

                try {
                    $image->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue avec l\'upload de votre logo. Veuillez retenter.');

                    return $this->redirectToRoute('app_club_edit');
                }
            }

            $clubRepository->add($club, true);
            $this->addFlash('success', 'Les informations de votre club ont été mises à jour');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('club/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
