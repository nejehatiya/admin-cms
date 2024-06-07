<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240525094912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_type ADD has_list TINYINT(1) NOT NULL, ADD slug_in_url TINYINT(1) DEFAULT NULL, CHANGE type_post_type type_post_type VARCHAR(255) DEFAULT NULL, CHANGE is_draft is_draft TINYINT(1) DEFAULT 0');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_type DROP has_list, DROP slug_in_url, CHANGE type_post_type type_post_type VARCHAR(255) NOT NULL, CHANGE is_draft is_draft INT DEFAULT 0');
    }
}
