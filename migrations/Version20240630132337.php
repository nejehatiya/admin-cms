<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240630132337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE modeles_content_default (id INT AUTO_INCREMENT NOT NULL, modele_id_id INT NOT NULL, content_default LONGTEXT NOT NULL, user VARCHAR(255) NOT NULL, INDEX IDX_17601F97DA88AC48 (modele_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE modeles_content_default ADD CONSTRAINT FK_17601F97DA88AC48 FOREIGN KEY (modele_id_id) REFERENCES modeles_post (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE modeles_content_default DROP FOREIGN KEY FK_17601F97DA88AC48');
        $this->addSql('DROP TABLE modeles_content_default');
    }
}
