<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200424132417 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post CHANGE updated updated DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE blog CHANGE updated updated DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tag CHANGE updated updated DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD password VARCHAR(255) NOT NULL, CHANGE updated updated DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog CHANGE updated updated DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE post CHANGE updated updated DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE tag CHANGE updated updated DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user DROP password, CHANGE updated updated DATETIME DEFAULT \'NULL\'');
    }
}
