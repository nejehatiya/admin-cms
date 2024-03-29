<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240329001559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE modeles_post_post_type (modeles_post_id INT NOT NULL, post_type_id INT NOT NULL, INDEX IDX_6679276F33DCADE4 (modeles_post_id), INDEX IDX_6679276FF8A43BA0 (post_type_id), PRIMARY KEY(modeles_post_id, post_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE modeles_post_post_type ADD CONSTRAINT FK_6679276F33DCADE4 FOREIGN KEY (modeles_post_id) REFERENCES modeles_post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE modeles_post_post_type ADD CONSTRAINT FK_6679276FF8A43BA0 FOREIGN KEY (post_type_id) REFERENCES post_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE modeles_post ADD image_preview_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE modeles_post ADD CONSTRAINT FK_D20DEBC4A13A78ED FOREIGN KEY (image_preview_id) REFERENCES images (id)');
        $this->addSql('CREATE INDEX IDX_D20DEBC4A13A78ED ON modeles_post (image_preview_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE modeles_post_post_type DROP FOREIGN KEY FK_6679276F33DCADE4');
        $this->addSql('ALTER TABLE modeles_post_post_type DROP FOREIGN KEY FK_6679276FF8A43BA0');
        $this->addSql('DROP TABLE modeles_post_post_type');
        $this->addSql('ALTER TABLE modeles_post DROP FOREIGN KEY FK_D20DEBC4A13A78ED');
        $this->addSql('DROP INDEX IDX_D20DEBC4A13A78ED ON modeles_post');
        $this->addSql('ALTER TABLE modeles_post DROP image_preview_id');
    }
}
