<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241204221510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // Ajouter la colonne duration avec une valeur par défaut de 120
        $this->addSql('ALTER TABLE movie ADD duration INT DEFAULT 120 NOT NULL');

        // Ajouter la colonne trailers sans valeur par défaut, mais gérer le NULL pour éviter les erreurs
        $this->addSql('ALTER TABLE movie ADD trailers JSON NULL');

        // Mettre à jour les anciennes lignes pour garantir que trailers ne soit jamais NULL
        $this->addSql('UPDATE movie SET trailers = "[]" WHERE trailers IS NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie DROP duration, DROP trailers');
    }
}
