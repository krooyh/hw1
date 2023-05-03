<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230502211842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add departments table.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE departments (id VARCHAR(26) NOT NULL, name VARCHAR(255) NOT NULL, salary_bonus_type SMALLINT UNSIGNED NOT NULL, salary_bonus_value NUMERIC(8, 2) NOT NULL, UNIQUE INDEX UNIQ_16AEB8D45E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE departments');
    }
}
