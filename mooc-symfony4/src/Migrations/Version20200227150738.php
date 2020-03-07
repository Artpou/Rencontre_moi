<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200227150738 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sport (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hobbis CHANGE categories_id categories_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD profil_picture VARCHAR(255) DEFAULT NULL, CHANGE role_id role_id INT DEFAULT NULL, CHANGE sex_id sex_id INT DEFAULT NULL, CHANGE preference_id preference_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sport');
        $this->addSql('ALTER TABLE hobbis CHANGE categories_id categories_id INT NOT NULL');
        $this->addSql('ALTER TABLE user DROP profil_picture, CHANGE sex_id sex_id INT NOT NULL, CHANGE role_id role_id INT NOT NULL, CHANGE preference_id preference_id INT NOT NULL');
    }
}
