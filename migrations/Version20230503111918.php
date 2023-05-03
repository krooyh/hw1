<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503111918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add employees table.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employees (id VARCHAR(26) NOT NULL, name VARCHAR(20) NOT NULL, surname VARCHAR(30) NOT NULL, employment_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', termination_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', department_id VARCHAR(26) NOT NULL, monthly_salary NUMERIC(8, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE employees');
    }
}
