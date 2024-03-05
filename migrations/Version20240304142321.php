<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240304142321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shipment ADD sender_address VARCHAR(255) NOT NULL, ADD delivery_address VARCHAR(255) NOT NULL, DROP sender_adress, DROP delivery_adress');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shipment ADD sender_adress VARCHAR(255) NOT NULL, ADD delivery_adress VARCHAR(255) NOT NULL, DROP sender_address, DROP delivery_address');
    }
}
