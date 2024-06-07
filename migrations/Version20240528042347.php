<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240528042347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post_meta_fields (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, fields LONGTEXT NOT NULL, status TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_meta_fields_post_type (post_meta_fields_id INT NOT NULL, post_type_id INT NOT NULL, INDEX IDX_5ECA6C0A49B5DC2E (post_meta_fields_id), INDEX IDX_5ECA6C0AF8A43BA0 (post_type_id), PRIMARY KEY(post_meta_fields_id, post_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_meta_fields_post_type ADD CONSTRAINT FK_5ECA6C0A49B5DC2E FOREIGN KEY (post_meta_fields_id) REFERENCES post_meta_fields (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_meta_fields_post_type ADD CONSTRAINT FK_5ECA6C0AF8A43BA0 FOREIGN KEY (post_type_id) REFERENCES post_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE template_page ADD status TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_meta_fields_post_type DROP FOREIGN KEY FK_5ECA6C0A49B5DC2E');
        $this->addSql('ALTER TABLE post_meta_fields_post_type DROP FOREIGN KEY FK_5ECA6C0AF8A43BA0');
        $this->addSql('DROP TABLE post_meta_fields');
        $this->addSql('DROP TABLE post_meta_fields_post_type');
        $this->addSql('ALTER TABLE template_page DROP status');
    }
}
