<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200429145001 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog CHANGE updated updated DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE category CHANGE updated updated DATETIME DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C1DAE07E975E237E06 ON category (blog_id, name)');
        $this->addSql('ALTER TABLE comment CHANGE updated updated DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE notification CHANGE updated updated DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE post CHANGE category_id category_id INT DEFAULT NULL, CHANGE updated updated DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE subscriber CHANGE updated updated DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tag CHANGE updated updated DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE updated updated DATETIME DEFAULT NULL, CHANGE api_token api_token VARCHAR(255) DEFAULT NULL, CHANGE password password VARCHAR(255) DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog CHANGE updated updated DATETIME DEFAULT \'NULL\'');
        $this->addSql('DROP INDEX UNIQ_64C19C1DAE07E975E237E06 ON category');
        $this->addSql('ALTER TABLE category CHANGE updated updated DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE comment CHANGE updated updated DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE notification CHANGE updated updated DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE post CHANGE category_id category_id INT DEFAULT NULL, CHANGE updated updated DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE subscriber CHANGE updated updated DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE tag CHANGE updated updated DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE updated updated DATETIME DEFAULT \'NULL\', CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE password password VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_unicode_ci`, CHANGE api_token api_token VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_unicode_ci`');
    }
}
