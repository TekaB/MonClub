<?php

namespace App\Controller;

use App\Entity\Rencontre;
use App\Form\NewRencontreType;
use App\Repository\EquipeRepository;
use App\Repository\RencontreRepository;
use App\Service\BrulageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RencontreController extends AbstractController
{
    #[Route('/rencontre/', name: 'app_rencontre')]
    public function index(): Response
    {
        return $this->render('rencontre/index.html.twig', [
            'controller_name' => 'RencontreController',
        ]);
    }

    /**
     * @param string $id id de l'équipe qui fait la rencontre /!\ pour retrouver les joueurs
     */
    #[Route('/rencontre/{id}/new', name: 'app_rencontre_new')]
    public function new(
        string $id,
        EquipeRepository $equipeRepository,
        Request $request,
        RencontreRepository $rencontreRepository,
        BrulageService $brulageService
    ): Response {
        // Créer une à partir de l'id d'une équipe afin de choper les joueurs qui sont dedans
        $equipe = $equipeRepository->find($id);
        $rencontre = new Rencontre();
        $infosBrulage = $brulageService->checkBrulage($equipe->getJoueurs()->toArray());

        // On enregistre les joueurs qui participe à cette rencontre
        foreach ($equipe->getJoueurs() as $joueur) {
            $rencontre->addJoueur($joueur);
        }
        $rencontre->setNumeroEquipe($equipe->getNumero());
        $form = $this->createForm(NewRencontreType::class, $rencontre, [
            'joueurs' => $equipe->getJoueurs(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($infosBrulage as $brulage) {
                if ($brulage < $equipe->getNumero()) {
                    $this->addFlash('error', 'Un joueur est brûlé dans cette équipe. La rencontre n\'a pas été validé.');

                    return $this->redirectToRoute('app_rencontre_new', ['id' => $equipe->getId()]);
                }
            }

            $rencontreRepository->add($rencontre, true);
            $this->addFlash('success', 'Rencontre enregistrée !');

            return $this->redirectToRoute('app_equipe');
        }

        return $this->render('rencontre/new.html.twig', [
            'form' => $form->createView(),
            'equipe' => $equipe,
            'infosBrulage' => $infosBrulage,
        ]);
    }
}
