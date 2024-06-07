<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240528080151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE template_page_post_type (template_page_id INT NOT NULL, post_type_id INT NOT NULL, INDEX IDX_DB7471F75FFE18DD (template_page_id), INDEX IDX_DB7471F7F8A43BA0 (post_type_id), PRIMARY KEY(template_page_id, post_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE template_page_post_type ADD CONSTRAINT FK_DB7471F75FFE18DD FOREIGN KEY (template_page_id) REFERENCES template_page (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE template_page_post_type ADD CONSTRAINT FK_DB7471F7F8A43BA0 FOREIGN KEY (post_type_id) REFERENCES post_type (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE template_page_post_type DROP FOREIGN KEY FK_DB7471F75FFE18DD');
        $this->addSql('ALTER TABLE template_page_post_type DROP FOREIGN KEY FK_DB7471F7F8A43BA0');
        $this->addSql('DROP TABLE template_page_post_type');
    }
}
