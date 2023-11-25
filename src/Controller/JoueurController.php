<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Form\NewJoueurType;
use App\Repository\JoueurRepository;
use App\Service\BrulageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JoueurController extends AbstractController
{
    #[Route('/joueurs', name: 'app_joueur')]
    public function index(JoueurRepository $joueurRepository, BrulageService $brulageService): Response
    {
        $joueurCompet = $joueurRepository->findBy(['typeLicence' => Joueur::TYPELICENCE['Compétition']], ['points' => 'DESC']);
        $brulagesCompet = $brulageService->checkBrulage($joueurCompet);

        return $this->render('joueur/index.html.twig', [
            'joueursNonCompet' => $joueurRepository->findJoueurNonCompet(),
            'joueursCompet' => $joueurCompet,
            'brulageCompet' => $brulagesCompet
        ]);
    }

    #[Route('/joueurs/new', name: 'app_joueur_new')]
    public function new(JoueurRepository $joueurRepository, Request $request): Response
    {
        $joueur = new Joueur();
        $joueur->setPoints(500);
        $joueur->setTypeLicence(Joueur::TYPELICENCE['Loisir']);
        $form = $this->createForm(NewJoueurType::class, $joueur);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $joueurRepository->add($joueur, true);
            $this->addFlash('success', 'Joueur ajouté !');

            if ('addAndStartAgain' === $form->getClickedButton()->getName()) {
                return $this->redirectToRoute('app_joueur_new');
            }

            return $this->redirectToRoute('app_joueur');
        }

        return $this->render('joueur/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/joueurs/{id}/edit', name: 'app_joueur_edit')]
    public function edit(string $id, JoueurRepository $joueurRepository, Request $request, BrulageService $brulageService): Response
    {
        $joueur = $joueurRepository->find($id);
        $infosBrulage = $brulageService->checkBrulage([$joueur]);
        $form = $this->createForm(NewJoueurType::class, $joueur);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $joueurRepository->add($joueur, true);
            $this->addFlash('success', 'Joueur mis à jour');

            return $this->redirectToRoute('app_joueur');
        }

        return $this->render('joueur/edit.html.twig', [
            'form' => $form,
            'joueur' => $joueur,
            'infosBrulage' => $infosBrulage
        ]);
    }

    #[Route('/joueurs/{id}/delete', name: 'app_joueur_delete')]
    public function delete(string $id, JoueurRepository $joueurRepository): Response
    {
        $joueurRepository->remove($joueurRepository->find($id), true);
        $this->addFlash('notice', 'Joueur supprimé');

        return $this->redirectToRoute('app_joueur');
    }
}
