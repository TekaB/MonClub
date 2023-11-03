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

    public function checkBrulage(array $joueurs)
    {
        $brulages = [];
        $resultat = [];

        foreach ($joueurs as $joueur) {
            $brulages[$joueur->getId()] = $this->joueurRepository->findJoueurHighestBrulage($joueur);
        }

        foreach($brulages as $cle => $sousTableau) {
            foreach($sousTableau as $element){
                if (isset($element['numeroEquipe'])) {
                    $resultat[$cle] = $element['numeroEquipe'];
                    break; // Sortir de la boucle interne une fois que la valeur est récupérée
                }
            }
        }

        return $resultat;
    }
}
