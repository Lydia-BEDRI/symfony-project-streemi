<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241126111454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Make label and description columns non-nullable with default values';
    }

    public function up(Schema $schema): void
    {
        // Update the label and description columns to NOT NULL and set default empty strings
        $this->addSql('ALTER TABLE subscription CHANGE label label VARCHAR(255) NOT NULL DEFAULT \'\', CHANGE description description LONGTEXT NOT NULL DEFAULT \'\'');
    }

    public function down(Schema $schema): void
    {
        // Rollback the changes
        $this->addSql('ALTER TABLE subscription CHANGE label label VARCHAR(255) DEFAULT NULL, CHANGE description description LONGTEXT DEFAULT NULL');
    }
}
