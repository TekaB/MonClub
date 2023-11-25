<?php

namespace App\Service;

use App\Repository\JoueurRepository;

class BrulageService
{
    private $joueurRepository;

    public function __construct(JoueurRepository $joueurRepository)
    {
        $this->joueurRepository = $joueurRepository;
    }

    /**
     * Returns the nb of team where each player is burnt as [idPlayer => numberTeam] for each player given.
     *
     * @param array $joueurs the array of players
     */
    public function checkBrulage(array $joueurs): array
    {
        $highestMatches = [];
        $joueurEquipeBrule = [];

        foreach ($joueurs as $joueur) {
            $highestMatches[$joueur->getId()] = $this->joueurRepository->findJoueurHighestMatches($joueur);
        }

        foreach ($highestMatches as $idJoueur => $match) {
            if (count($match) > 0) {
                $joueurEquipeBrule[$idJoueur] = end($highestMatches[$idJoueur])['numeroEquipe'];
            }
        }

        return $joueurEquipeBrule;
    }
}
