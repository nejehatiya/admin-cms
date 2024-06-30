<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240627200043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499D30A56E');
        $this->addSql('DROP INDEX IDX_8D93D6499D30A56E ON user');
        $this->addSql('ALTER TABLE user DROP roles_user_id, DROP date_upd');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD roles_user_id INT DEFAULT NULL, ADD date_upd DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499D30A56E FOREIGN KEY (roles_user_id) REFERENCES roles (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6499D30A56E ON user (roles_user_id)');
    }
}
