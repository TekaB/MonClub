<?php

namespace App\DataFixtures;

use App\Entity\Club;
use App\Entity\Equipe;
use App\Entity\Joueur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $club = (new Club())->setNom('TTSM Club 2 merde');
        $pierre = (new Joueur())
            ->setNom('LICTEVOUT')
            ->setPrenom('Pierre')
            ->setNumeroLicence("6211534")
            ->setPoints(890)
            ->setTypeLicence(Joueur::TYPELICENCE['Compétition']);
        $kev = (new Joueur())
            ->setNom('BELKA')
            ->setPrenom('Kévin')
            ->setNumeroLicence("628519")
            ->setPoints(1732)
            ->setTypeLicence(Joueur::TYPELICENCE['Compétition']);
        $rem = (new Joueur())
            ->setNom('KOSTRZEWKSI')
            ->setPrenom('Rémy')
            ->setNumeroLicence("629209")
            ->setPoints(2067)
            ->setTypeLicence(Joueur::TYPELICENCE['Compétition']);
        $jo = (new Joueur())
            ->setNom('LECLAIRE')
            ->setPrenom('Johann')
            ->setNumeroLicence("625250")
            ->setPoints(1800)
            ->setTypeLicence(Joueur::TYPELICENCE['Compétition']);
        $evenementiel = (new Joueur())
            ->setNom('Evenemential')
            ->setPrenom('Titi')
            ->setNumeroLicence("0101010")
            ->setPoints(500)
            ->setTypeLicence(Joueur::TYPELICENCE['Evénementiel']);
        $loisir = (new Joueur())
            ->setNom('LOISIR')
            ->setPrenom('Toto')
            ->setNumeroLicence("1010101")
            ->setPoints(500)
            ->setTypeLicence(Joueur::TYPELICENCE['Loisir']);

        $equipe = (new Equipe())
            ->setNiveau(Equipe::NIVEAU['R1'])
            ->setPriorite(1)
            ->addJoueur($pierre)
            ->addJoueur($rem)
            ->addJoueur($jo)
            ->addJoueur($kev);

        $emptyTeam = (new Equipe())->setNiveau(Equipe::NIVEAU['D3'])->setPriorite(2);

        $noteam1 = (new Joueur())
            ->setTypeLicence(Joueur::TYPELICENCE['Compétition'])
            ->setNom('DESPREZ')
            ->setPoints(697)
            ->setPrenom("Teddy")
            ->setNumeroLicence("6211165");
        $noteam2 = (new Joueur())
            ->setTypeLicence(Joueur::TYPELICENCE['Compétition'])
            ->setNom('LECIGNE')
            ->setPoints(673)
            ->setPrenom("Jessy")
            ->setNumeroLicence("6229957");
        $noteam3 = (new Joueur())
            ->setTypeLicence(Joueur::TYPELICENCE['Compétition'])
            ->setNom('BERTHIER')
            ->setPoints(500)
            ->setPrenom("Christophe")
            ->setNumeroLicence("6241075");
        $noteam4 = (new Joueur())
            ->setTypeLicence(Joueur::TYPELICENCE['Compétition'])
            ->setNom('JUMETZ')
            ->setPoints(498)
            ->setPrenom("Titouan")
            ->setNumeroLicence("6228673");

        $manager->persist($pierre);
        $manager->persist($kev);
        $manager->persist($rem);
        $manager->persist($jo);
        $manager->persist($evenementiel);
        $manager->persist($loisir);
        $manager->persist($equipe);
        $manager->persist($emptyTeam);
        $manager->persist($noteam1);
        $manager->persist($noteam2);
        $manager->persist($noteam3);
        $manager->persist($noteam4);
        $manager->persist($club);

        $manager->flush();
    }
}
