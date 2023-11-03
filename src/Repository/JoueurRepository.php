<?php

namespace App\Repository;

use App\Entity\Joueur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Joueur>
 *
 * @method Joueur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Joueur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Joueur[]    findAll()
 * @method Joueur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JoueurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Joueur::class);
    }

    public function add(Joueur $joueur, $flush = false): void
    {
        $em = $this->getEntityManager();
        $em->persist($joueur);

        if ($flush) {
            $em->flush();
        }
    }

    public function remove(Joueur $joueur, $flush = false): void
    {
        $em = $this->getEntityManager();
        $em->remove($joueur);

        if ($flush) {
            $em->flush();
        }
    }

    public function findJoueurWithTeam(): array
    {
        return $this->createQueryBuilder('j')
            ->where('j.equipe IS NOT NULL')
            ->orderBy('j.points', 'DESC')
            ->getQuery()->getResult();
    }

    public function findJoueurNonCompet(): array
    {
        return $this->createQueryBuilder('j')
            ->where('j.typeLicence NOT LIKE \'Compétitif\'')
            ->orderBy('j.points', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findJoueurHighestBrulage(Joueur $joueur)
    {
        return $this->createQueryBuilder('j')
            ->select('jr.numeroEquipe')
            ->join('j.rencontres', 'jr')
            ->where('j = :joueur')
            ->groupBy('jr.numeroEquipe')
            ->having('count(jr) > 1')
            ->orderBy('jr.numeroEquipe', 'ASC')
            ->setParameter('joueur', $joueur)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }
}
