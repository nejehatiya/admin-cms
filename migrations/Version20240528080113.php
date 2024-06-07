<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240528080113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE template_page DROP FOREIGN KEY FK_C1C49C4DF8A43BA0');
        $this->addSql('DROP INDEX IDX_C1C49C4DF8A43BA0 ON template_page');
        $this->addSql('ALTER TABLE template_page DROP post_type_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE template_page ADD post_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE template_page ADD CONSTRAINT FK_C1C49C4DF8A43BA0 FOREIGN KEY (post_type_id) REFERENCES post_type (id)');
        $this->addSql('CREATE INDEX IDX_C1C49C4DF8A43BA0 ON template_page (post_type_id)');
    }
}
