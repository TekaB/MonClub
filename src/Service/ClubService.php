<?php

namespace App\Service;

use App\Entity\Club;
use App\Repository\ClubRepository;

class ClubService
{
    private $clubRepository;

    public function __construct(ClubRepository $clubRepository)
    {
        $this->clubRepository = $clubRepository;
    }

    /**
     * Retourne le club s'il est déjà parametré.
     * Renvoie un nouveau club sinon
     */
    public function getClub()
    {
        $club = $this->clubRepository->findBy([], [], 1);

        return $club ? $club[0] : new Club();
    }
}
