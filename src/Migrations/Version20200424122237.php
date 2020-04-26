<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200424122237 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog ADD created DATETIME NOT NULL, ADD updated DATETIME DEFAULT NULL, DROP origin, DROP api_key');
        $this->addSql('ALTER TABLE post ADD created DATETIME NOT NULL, ADD updated DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tag ADD created DATETIME NOT NULL, ADD updated DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog ADD origin VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, ADD api_key VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, DROP created, DROP updated');
        $this->addSql('ALTER TABLE post DROP created, DROP updated');
        $this->addSql('ALTER TABLE tag DROP created, DROP updated');
    }
}
