<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307164352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shipment (
            id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
            country VARCHAR(255) NOT NULL,
            company VARCHAR(255) DEFAULT NULL,
            first_name VARCHAR(255) NOT NULL,
            last_name VARCHAR(255) NOT NULL,
            address1 VARCHAR(255) NOT NULL,
            address2 VARCHAR(255) DEFAULT NULL,
            postcode VARCHAR(255) NOT NULL,
            city VARCHAR(255) NOT NULL,
            phone VARCHAR(255) DEFAULT NULL,
            phone_mobile VARCHAR(255) NOT NULL,
            barcode VARCHAR(255) DEFAULT NULL
        )');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE shipment');
    }
}

