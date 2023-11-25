<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Entity\Joueur;
use App\Form\ModifierEquipeType;
use App\Form\NewEquipeType;
use App\Repository\EquipeRepository;
use App\Repository\JoueurRepository;
use App\Service\BrulageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EquipeController extends AbstractController
{
    #[Route('/equipe', name: 'app_equipe')]
    public function index(EquipeRepository $equipeRepository, Request $request, JoueurRepository $joueurRepository, BrulageService $brulageService): Response
    {
        $allEquipes = $equipeRepository->findBy([], ['numero' => 'ASC']);
        $infosBrulage = [];

        foreach ($allEquipes as $equipe) {
            $brulages = $brulageService->checkBrulage($equipe->getJoueurs()->toArray());

            foreach ($brulages as $key => $brulage) {
                $infosBrulage[$key] = $brulage;
            }
        }

        $nbUncompleteTeam = 0;
        $nbAvailablePlayer = count($joueurRepository->findBy(['equipe' => null, 'typeLicence' => Joueur::TYPELICENCE['Compétition']]));
        $nbEquipe = count($allEquipes);
        $equipe = (new Equipe())->setPriorite(-1)->setNumero(++$nbEquipe);
        $form = $this->createForm(NewEquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $equipe->setPriorite(Equipe::DEFAULT_PRIORITY[$equipe->getNiveau()]);
            $equipeRepository->add($equipe, true);

            return $this->redirectToRoute('app_equipe');
        }

        foreach ($allEquipes as $equipe) {
            if (count($equipe->getJoueurs()) < 4) {
                ++$nbUncompleteTeam;
            }
        }

        return $this->render('equipe/index.html.twig', [
            'equipes' => $allEquipes,
            'form' => $form,
            'nbUncompleteTeam' => $nbUncompleteTeam,
            'nbAvailablePlayer' => $nbAvailablePlayer,
            'infosBrulage' => $infosBrulage,
        ]);
    }

    #[Route('/equipe/{id}/edit', name: 'app_equipe_edit')]
    public function edit(string $id, EquipeRepository $equipeRepository, Request $request): Response
    {
        $equipe = $equipeRepository->find($id);
        $form = $this->createForm(ModifierEquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $equipeRepository->add($equipe, true);
            $this->addFlash('success', 'Equipe mise à jour');

            return $this->redirectToRoute('app_equipe');
        }

        return $this->render('equipe/editEquipe.html.twig', [
            'form' => $form,
            'equipe' => $equipe,
        ]);
    }

    #[Route('/equipe/{id}/delete', name: 'app_equipe_delete')]
    public function delete(string $id, EquipeRepository $equipeRepository)
    {
        $equipe = $equipeRepository->find($id);

        foreach ($equipe->getJoueurs() as $joueur) {
            $equipe->removeJoueur($joueur);
        }

        $equipeRepository->remove($equipeRepository->find($id), true);
        $this->addFlash('notice', 'Equipe supprimée !');

        return $this->redirectToRoute('app_equipe');
    }

    #[Route('/equipe/{id}/autocomplete', name: 'app_equipe_autocomplete')]
    public function autocomplete(string $id, EquipeRepository $equipeRepository, JoueurRepository $joueurRepository)
    {
        $equipe = $equipeRepository->find($id);
        $nbJoueursDansEquipe = count($equipe->getJoueurs());
        $joueurs = $joueurRepository->findBy(
            ['equipe' => null, 'typeLicence' => Joueur::TYPELICENCE['Compétition']],
            ['points' => 'DESC'],
            Equipe::MAXJOUEUR - $nbJoueursDansEquipe
        );

        foreach ($joueurs as $joueur) {
            $equipe->addJoueur($joueur);
        }

        $equipeRepository->add($equipe, true);

        return $this->redirectToRoute('app_equipe');
    }

    #[Route('/equipe/autocomplete', name: 'app_equipe_autocompleteall')]
    public function multipleAutocomplete(EquipeRepository $equipeRepository, JoueurRepository $joueurRepository, EntityManagerInterface $manager): Response
    {
        $equipes = $equipeRepository->findIncompleteTeam();
        $joueurs = $joueurRepository->findBy(
            ['equipe' => null, 'typeLicence' => Joueur::TYPELICENCE['Compétition']],
            ['points' => 'DESC'],
        );

        foreach ($equipes as $equipe) {
            $nbJoueursDansEquipe = count($equipe->getJoueurs());

            foreach ($joueurs as $key => $joueur) {
                if ($nbJoueursDansEquipe < 4) {
                    $equipe->addJoueur($joueur);
                    unset($joueurs[$key]);
                    ++$nbJoueursDansEquipe;
                }
            }

            $equipeRepository->add($equipe);
        }

        $manager->flush();

        $this->addFlash('success', 'Tada ! Vos équipes ont été remplie au maximum !');

        return $this->redirectToRoute('app_equipe');
    }
}
