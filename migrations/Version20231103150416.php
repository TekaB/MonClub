<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231103150416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE rencontre_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE rencontre (id INT NOT NULL, numero_equipe INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE rencontre_joueur (rencontre_id INT NOT NULL, joueur_id INT NOT NULL, PRIMARY KEY(rencontre_id, joueur_id))');
        $this->addSql('CREATE INDEX IDX_490DF56F6CFC0818 ON rencontre_joueur (rencontre_id)');
        $this->addSql('CREATE INDEX IDX_490DF56FA9E2D76C ON rencontre_joueur (joueur_id)');
        $this->addSql('ALTER TABLE rencontre_joueur ADD CONSTRAINT FK_490DF56F6CFC0818 FOREIGN KEY (rencontre_id) REFERENCES rencontre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rencontre_joueur ADD CONSTRAINT FK_490DF56FA9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE rencontre_id_seq CASCADE');
        $this->addSql('ALTER TABLE rencontre_joueur DROP CONSTRAINT FK_490DF56F6CFC0818');
        $this->addSql('ALTER TABLE rencontre_joueur DROP CONSTRAINT FK_490DF56FA9E2D76C');
        $this->addSql('DROP TABLE rencontre');
        $this->addSql('DROP TABLE rencontre_joueur');
    }
}
