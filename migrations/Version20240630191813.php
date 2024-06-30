<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240630191813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post_analyse (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, user_id INT NOT NULL, post_type_id INT NOT NULL, a_link LONGTEXT DEFAULT NULL, img_src LONGTEXT DEFAULT NULL, h_heading LONGTEXT NOT NULL, date_add DATETIME DEFAULT NULL, date_upd DATETIME NOT NULL, states VARCHAR(255) NOT NULL, count_word BIGINT DEFAULT NULL, ratio_html BIGINT NOT NULL, a_link_text LONGTEXT DEFAULT NULL, code_reponse VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8F3BB4D4B89032C (post_id), INDEX IDX_8F3BB4DA76ED395 (user_id), INDEX IDX_8F3BB4DF8A43BA0 (post_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_analyse ADD CONSTRAINT FK_8F3BB4D4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post_analyse ADD CONSTRAINT FK_8F3BB4DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post_analyse ADD CONSTRAINT FK_8F3BB4DF8A43BA0 FOREIGN KEY (post_type_id) REFERENCES post_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_analyse DROP FOREIGN KEY FK_8F3BB4D4B89032C');
        $this->addSql('ALTER TABLE post_analyse DROP FOREIGN KEY FK_8F3BB4DA76ED395');
        $this->addSql('ALTER TABLE post_analyse DROP FOREIGN KEY FK_8F3BB4DF8A43BA0');
        $this->addSql('DROP TABLE post_analyse');
    }
}
