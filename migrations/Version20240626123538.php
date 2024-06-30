<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240626123538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE permission_roles_routes DROP FOREIGN KEY FK_B0E1AB2734ECB4E6');
        $this->addSql('ALTER TABLE permission_roles_routes DROP FOREIGN KEY FK_B0E1AB2738C751C4');
        $this->addSql('DROP TABLE permission_roles_routes');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE permission_roles_routes (route_id INT NOT NULL, roles_id INT NOT NULL, INDEX IDX_B0E1AB2738C751C4 (roles_id), INDEX IDX_B0E1AB2734ECB4E6 (route_id), PRIMARY KEY(route_id, roles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE permission_roles_routes ADD CONSTRAINT FK_B0E1AB2734ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permission_roles_routes ADD CONSTRAINT FK_B0E1AB2738C751C4 FOREIGN KEY (roles_id) REFERENCES roles (id) ON DELETE CASCADE');
    }
}
