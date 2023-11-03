<?php

namespace App\Service;

class RencontreService
{
    public function getRencontreParNumEquipe($rencontres): array
    {
        $rencontresParEquipe = [];

        foreach ($rencontres as $rencontre) {
            $rencontresParEquipe[$rencontre->getNumeroEquipe()][] = $rencontre;
        }

        ksort($rencontresParEquipe);

        return $rencontresParEquipe;
    }

    public function getNbRencontreParNumEquipe($rencontresParEquipe): array
    {
        $nbRencontreParEquipe = [];

        foreach ($rencontresParEquipe as $key => $rencontre) {
            $nbRencontreParEquipe[$key] = count($rencontre);
        }

        return $nbRencontreParEquipe;
    }
}
