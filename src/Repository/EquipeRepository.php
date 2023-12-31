<?php

namespace App\Repository;

use App\Entity\Equipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Equipe>
 *
 * @method Equipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Equipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Equipe[]    findAll()
 * @method Equipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipe::class);
    }

    public function add(Equipe $equipe, $flush = false): void
    {
        $em = $this->getEntityManager();
        $em->persist($equipe);

        if ($flush) {
            $em->flush();
        }
    }

    public function remove(Equipe $equipe, $flush = false): void
    {
        $em = $this->getEntityManager();
        $em->remove($equipe);

        if ($flush) {
            $em->flush();
        }
    }

    public function findIncompleteTeam()
    {
        $equipes = $this->findBy([], ['priorite' => 'DESC']);

        foreach ($equipes as $key => $equipe) {
            if (count($equipe->getJoueurs()) >= 4) {
                unset($equipes[$key]);
            }
        }

        return $equipes;
    }
}
