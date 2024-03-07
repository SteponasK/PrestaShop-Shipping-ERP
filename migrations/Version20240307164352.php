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
        $this->addSql('ALTER TABLE shipment ADD country VARCHAR(255) NOT NULL, ADD company VARCHAR(255) DEFAULT NULL, ADD first_name VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL, ADD address1 VARCHAR(255) NOT NULL, ADD address2 VARCHAR(255) DEFAULT NULL, ADD postcode VARCHAR(255) NOT NULL, ADD city VARCHAR(255) NOT NULL, ADD phone VARCHAR(255) DEFAULT NULL, ADD phone_mobile VARCHAR(255) NOT NULL, DROP full_name, DROP phone_number, DROP sender_address, DROP delivery_address, CHANGE barcode barcode VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shipment ADD full_name VARCHAR(255) NOT NULL, ADD phone_number VARCHAR(255) NOT NULL, ADD sender_address VARCHAR(255) NOT NULL, ADD delivery_address VARCHAR(255) NOT NULL, DROP country, DROP company, DROP first_name, DROP last_name, DROP address1, DROP address2, DROP postcode, DROP city, DROP phone, DROP phone_mobile, CHANGE barcode barcode BIGINT DEFAULT NULL');
    }
}
