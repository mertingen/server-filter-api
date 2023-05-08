<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508155101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE server (id INT AUTO_INCREMENT NOT NULL, model VARCHAR(255) NOT NULL, ram_size INT NOT NULL, ram_size_type VARCHAR(2) NOT NULL, ram_type VARCHAR(7) NOT NULL, hdd_count INT NOT NULL, hdd_size INT NOT NULL, hdd_total_size INT NOT NULL, hdd_size_type VARCHAR(2) NOT NULL, hdd_type VARCHAR(10) NOT NULL, actual_ram_size VARCHAR(65) NOT NULL, actual_hdd_size VARCHAR(65) NOT NULL, location VARCHAR(255) NOT NULL, price NUMERIC(6, 2) NOT NULL, price_currency VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE server');
    }
}
