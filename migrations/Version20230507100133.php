<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230507100133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE server ADD actual_hdd_size INT NOT NULL, DROP hdd_actual_size, CHANGE price price NUMERIC(6, 2) NOT NULL, CHANGE price_currency price_currency VARCHAR(15) NOT NULL, CHANGE ram_size_type ram_size_type VARCHAR(2) NOT NULL, CHANGE ram_type ram_type VARCHAR(7) NOT NULL, CHANGE hdd_size_type hdd_size_type VARCHAR(2) NOT NULL, CHANGE hdd_type hdd_type VARCHAR(10) NOT NULL, CHANGE ram_actual_size actual_ram_size INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE server ADD ram_actual_size INT NOT NULL, ADD hdd_actual_size VARCHAR(255) NOT NULL, DROP actual_ram_size, DROP actual_hdd_size, CHANGE ram_size_type ram_size_type VARCHAR(255) NOT NULL, CHANGE ram_type ram_type VARCHAR(255) NOT NULL, CHANGE hdd_size_type hdd_size_type VARCHAR(255) NOT NULL, CHANGE hdd_type hdd_type VARCHAR(255) NOT NULL, CHANGE price price VARCHAR(255) NOT NULL, CHANGE price_currency price_currency VARCHAR(255) NOT NULL');
    }
}
