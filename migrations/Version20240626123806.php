<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240626123806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE routes_roles DROP FOREIGN KEY FK_5E8606E38C751C4');
        $this->addSql('ALTER TABLE routes_roles DROP FOREIGN KEY FK_5E8606EAE2C16DC');
        $this->addSql('DROP TABLE routes_roles');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE routes_roles (routes_id INT NOT NULL, roles_id INT NOT NULL, INDEX IDX_5E8606EAE2C16DC (routes_id), INDEX IDX_5E8606E38C751C4 (roles_id), PRIMARY KEY(routes_id, roles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE routes_roles ADD CONSTRAINT FK_5E8606E38C751C4 FOREIGN KEY (roles_id) REFERENCES roles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE routes_roles ADD CONSTRAINT FK_5E8606EAE2C16DC FOREIGN KEY (routes_id) REFERENCES routes (id) ON DELETE CASCADE');
    }
}
