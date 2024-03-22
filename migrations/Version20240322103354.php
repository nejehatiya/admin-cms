<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240322103354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE acf (id INT AUTO_INCREMENT NOT NULL, type_acf VARCHAR(255) DEFAULT NULL, field_label VARCHAR(255) DEFAULT NULL, field_name VARCHAR(255) DEFAULT NULL, field_content LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carrousel (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, title_carrousel LONGTEXT DEFAULT NULL, description_carrousel LONGTEXT DEFAULT NULL, button_carrousel LONGTEXT DEFAULT NULL, image_carrousel LONGTEXT DEFAULT NULL, date_add DATETIME NOT NULL, date_upd DATETIME NOT NULL, status TINYINT(1) NOT NULL, shortcode_carrousel VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_EF01B08812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_carrousel (id INT AUTO_INCREMENT NOT NULL, name_category VARCHAR(255) NOT NULL, slug_category VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cities (id INT UNSIGNED AUTO_INCREMENT NOT NULL, departments_id INT UNSIGNED NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, pattern VARCHAR(255) NOT NULL, postal_code VARCHAR(125) NOT NULL, gps_lat DOUBLE PRECISION NOT NULL, gps_lon DOUBLE PRECISION NOT NULL, population INT DEFAULT NULL, INDEX IDX_D95DB16BF1B3F295 (departments_id), INDEX Name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, id_post_id INT DEFAULT NULL, id_user_id INT DEFAULT NULL, comment_content LONGTEXT NOT NULL, note_comment BIGINT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email_comment VARCHAR(255) NOT NULL, date_add DATETIME NOT NULL, date_upd DATETIME NOT NULL, status TINYINT(1) NOT NULL, parent INT DEFAULT NULL, comment_agent VARCHAR(255) DEFAULT NULL, comment_id_migration INT DEFAULT NULL, INDEX IDX_67F068BC9514AA5C (id_post_id), INDEX IDX_67F068BC79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE configuration (id INT AUTO_INCREMENT NOT NULL, config_name LONGTEXT DEFAULT NULL, config_value LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, objet VARCHAR(255) DEFAULT NULL, message LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departments (id INT UNSIGNED AUTO_INCREMENT NOT NULL, regions_id INT UNSIGNED NOT NULL, code CHAR(10) NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, iso_code VARCHAR(255) NOT NULL, INDEX IDX_16AEB8D4FCE83E5F (regions_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emplacement (id INT AUTO_INCREMENT NOT NULL, menu_id INT DEFAULT NULL, user_id INT DEFAULT NULL, key_emplacement VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, date_add DATETIME NOT NULL, date_upd DATETIME NOT NULL, INDEX IDX_C0CF65F6CCD7E912 (menu_id), INDEX IDX_C0CF65F6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, url_image LONGTEXT NOT NULL, name_image VARCHAR(255) DEFAULT NULL, description_image LONGTEXT DEFAULT NULL, alt_image LONGTEXT DEFAULT NULL, date_add DATETIME NOT NULL, date_update DATETIME NOT NULL, height DOUBLE PRECISION DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images_post (images_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_60845D72D44F05E5 (images_id), INDEX IDX_60845D724B89032C (post_id), PRIMARY KEY(images_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mail_historique (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, message VARCHAR(255) DEFAULT NULL, ipadress VARCHAR(30) DEFAULT NULL, date DATETIME DEFAULT NULL, template VARCHAR(50) DEFAULT NULL, subject VARCHAR(50) DEFAULT NULL, nom_complet VARCHAR(30) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, template_menu_id INT DEFAULT NULL, user_id INT NOT NULL, name_menu VARCHAR(255) NOT NULL, menu_content LONGTEXT NOT NULL, status_menu TINYINT(1) NOT NULL, date_add DATETIME NOT NULL, date_update DATETIME NOT NULL, INDEX IDX_7D053A939F6F922B (template_menu_id), INDEX IDX_7D053A93A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE miens (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, post_id INT NOT NULL, date_modif DATETIME NOT NULL, restoration LONGTEXT DEFAULT NULL, INDEX IDX_9DC0930CA76ED395 (user_id), INDEX IDX_9DC0930C4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE migration_image (id INT AUTO_INCREMENT NOT NULL, url_images LONGTEXT NOT NULL, date_insert DATETIME NOT NULL, uploaded TINYINT(1) NOT NULL, id_image INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE migration_log (id INT AUTO_INCREMENT NOT NULL, post_type_id INT NOT NULL, post_traiter INT DEFAULT NULL, date DATETIME NOT NULL, UNIQUE INDEX UNIQ_399FA51EF8A43BA0 (post_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modeles_post (id INT AUTO_INCREMENT NOT NULL, name_modele LONGTEXT DEFAULT NULL, content_modele LONGTEXT DEFAULT NULL, shortcode_modele LONGTEXT DEFAULT NULL, path_modele LONGTEXT DEFAULT NULL, variable_modele LONGTEXT DEFAULT NULL, status_modele TINYINT(1) NOT NULL, date_add DATETIME NOT NULL, date_upd DATETIME NOT NULL, image_preview VARCHAR(255) DEFAULT NULL, used_in LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) DEFAULT NULL, status_email TINYINT(1) NOT NULL, date_add DATE NOT NULL, date_upd DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notifications (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, action LONGTEXT DEFAULT NULL, action_value LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_6000B0D379F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE options (id INT AUTO_INCREMENT NOT NULL, option_name VARCHAR(255) DEFAULT NULL, option_value LONGTEXT DEFAULT NULL, option_label VARCHAR(255) DEFAULT NULL, option_type VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D035FA87B62DD4E5 (option_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE path_template_menu (id INT AUTO_INCREMENT NOT NULL, path_menu VARCHAR(255) NOT NULL, nombre_entree INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, id_feature_image_id INT DEFAULT NULL, author_id INT DEFAULT NULL, post_type_id INT DEFAULT NULL, post_title LONGTEXT NOT NULL, post_content LONGTEXT NOT NULL, post_excerpt LONGTEXT NOT NULL, post_name LONGTEXT NOT NULL, post_parent BIGINT DEFAULT NULL, guide LONGTEXT DEFAULT NULL, menu_ordre BIGINT DEFAULT NULL, post_status LONGTEXT NOT NULL, comment_status TINYINT(1) NOT NULL, date_add DATETIME NOT NULL, date_upd DATETIME NOT NULL, post_content_2 LONGTEXT NOT NULL, post_order_content LONGTEXT DEFAULT NULL, post_parent_migration INT DEFAULT NULL, post_id__migration INT DEFAULT NULL, page_template VARCHAR(255) DEFAULT NULL, post_content_3 LONGTEXT DEFAULT NULL, post_content_4 LONGTEXT DEFAULT NULL, post_content_5 LONGTEXT DEFAULT NULL, post_order_content_preinsertion LONGTEXT DEFAULT NULL, is_draft INT DEFAULT NULL, page_menu TINYINT(1) NOT NULL, sommaire LONGTEXT DEFAULT NULL, is_index TINYINT(1) DEFAULT 1 NOT NULL, is_follow TINYINT(1) DEFAULT 1 NOT NULL, INDEX IDX_5A8A6C8DA7C2DF83 (id_feature_image_id), INDEX IDX_5A8A6C8DF675F31B (author_id), INDEX IDX_5A8A6C8DF8A43BA0 (post_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_editibale (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_date DATETIME NOT NULL, post_id INT NOT NULL, INDEX IDX_33277CCDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_meta (id INT AUTO_INCREMENT NOT NULL, id_post_id INT DEFAULT NULL, acf_id INT DEFAULT NULL, meta_key VARCHAR(255) NOT NULL, meta_value LONGTEXT DEFAULT NULL, INDEX IDX_1EA7733E9514AA5C (id_post_id), INDEX IDX_1EA7733E2BB5C43C (acf_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_modals (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, modele_id INT NOT NULL, content_modele_post LONGTEXT DEFAULT NULL, fields LONGTEXT DEFAULT NULL, INDEX IDX_D3FD7CB04B89032C (post_id), INDEX IDX_D3FD7CB0AC14B70A (modele_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_type (id INT AUTO_INCREMENT NOT NULL, name_post_type VARCHAR(255) NOT NULL, slug_post_type VARCHAR(255) NOT NULL, type_post_type VARCHAR(255) NOT NULL, statut_menu_side_bar TINYINT(1) DEFAULT NULL, order_post_type INT DEFAULT NULL, is_draft INT DEFAULT 0, display_in_sitemap TINYINT(1) DEFAULT NULL, path_parent_sitemap VARCHAR(255) DEFAULT NULL, meta_title_seo_sitmap VARCHAR(255) DEFAULT NULL, meta_description_seo_sitemap VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE redirection (id INT AUTO_INCREMENT NOT NULL, old_root VARCHAR(500) NOT NULL, new_root LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_224450F73E6978FE (old_root), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regions (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, iso_code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE revision (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, post_order_content LONGTEXT NOT NULL, date DATETIME NOT NULL, INDEX IDX_6D6315CC4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE route (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, method VARCHAR(10) NOT NULL, module VARCHAR(30) NOT NULL, priority INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permission_roles_routes (route_id INT NOT NULL, roles_id INT NOT NULL, INDEX IDX_B0E1AB2734ECB4E6 (route_id), INDEX IDX_B0E1AB2738C751C4 (roles_id), PRIMARY KEY(route_id, roles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_tarifs (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, status TINYINT(1) NOT NULL, date_add DATETIME NOT NULL, date_up DATETIME NOT NULL, content_tarifs LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sidebar_menu_admin (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name_menu VARCHAR(255) NOT NULL, name_path LONGTEXT NOT NULL, parent INT NOT NULL, status TINYINT(1) NOT NULL, date_add DATETIME NOT NULL, date_upd DATETIME NOT NULL, icon VARCHAR(255) DEFAULT NULL, menu_order_sidebar INT DEFAULT NULL, params LONGTEXT DEFAULT NULL, INDEX IDX_ADA6576DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sitemap (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(512) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taxonomy (id INT AUTO_INCREMENT NOT NULL, posttype_id INT DEFAULT NULL, name_taxonomy VARCHAR(255) DEFAULT NULL, slug_taxonomy VARCHAR(255) DEFAULT NULL, description_taxonomy LONGTEXT DEFAULT NULL, parent_taxonomy BIGINT NOT NULL, autre_taxonomy LONGTEXT DEFAULT NULL, order_taxonomy INT DEFAULT NULL, statut_side_bar TINYINT(1) DEFAULT NULL, statut_menu TINYINT(1) DEFAULT NULL, is_draft INT DEFAULT NULL, INDEX IDX_FD12B83DC5D8F75 (posttype_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE template_menu (id INT AUTO_INCREMENT NOT NULL, path_template_id INT DEFAULT NULL, name_template VARCHAR(255) NOT NULL, content_template LONGTEXT NOT NULL, date_add DATETIME NOT NULL, date_upd DATETIME NOT NULL, status_template TINYINT(1) NOT NULL, INDEX IDX_A8CB10FE4F992803 (path_template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE terms (id INT AUTO_INCREMENT NOT NULL, id_taxonomy_id INT DEFAULT NULL, image_id INT DEFAULT NULL, name_terms LONGTEXT DEFAULT NULL, description_terms LONGTEXT DEFAULT NULL, slug_terms LONGTEXT DEFAULT NULL, autre_taxonomy LONGTEXT DEFAULT NULL, parent_terms INT DEFAULT NULL, id_migration VARCHAR(255) DEFAULT NULL, parent_migration VARCHAR(255) DEFAULT NULL, level INT DEFAULT NULL, is_draft INT DEFAULT NULL, INDEX IDX_88A23F718D452B01 (id_taxonomy_id), INDEX IDX_88A23F713DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE terms_post (terms_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_42F3A65353742F27 (terms_id), INDEX IDX_42F3A6534B89032C (post_id), PRIMARY KEY(terms_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, roles_user_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, token_key VARCHAR(255) NOT NULL, image_profil VARCHAR(255) DEFAULT NULL, status TINYINT(1) NOT NULL, date_add DATETIME NOT NULL, date_upd DATETIME NOT NULL, auth_code VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D6499D30A56E (roles_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visiteur_post (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, post_id INT NOT NULL, date_enter DATETIME NOT NULL, date_sortie DATETIME NOT NULL, INDEX IDX_E6DF2CC1A76ED395 (user_id), INDEX IDX_E6DF2CC14B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE carrousel ADD CONSTRAINT FK_EF01B08812469DE2 FOREIGN KEY (category_id) REFERENCES category_carrousel (id)');
        $this->addSql('ALTER TABLE cities ADD CONSTRAINT FK_D95DB16BF1B3F295 FOREIGN KEY (departments_id) REFERENCES departments (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC9514AA5C FOREIGN KEY (id_post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE departments ADD CONSTRAINT FK_16AEB8D4FCE83E5F FOREIGN KEY (regions_id) REFERENCES regions (id)');
        $this->addSql('ALTER TABLE emplacement ADD CONSTRAINT FK_C0CF65F6CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE emplacement ADD CONSTRAINT FK_C0CF65F6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE images_post ADD CONSTRAINT FK_60845D72D44F05E5 FOREIGN KEY (images_id) REFERENCES images (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE images_post ADD CONSTRAINT FK_60845D724B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A939F6F922B FOREIGN KEY (template_menu_id) REFERENCES template_menu (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE miens ADD CONSTRAINT FK_9DC0930CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE miens ADD CONSTRAINT FK_9DC0930C4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE migration_log ADD CONSTRAINT FK_399FA51EF8A43BA0 FOREIGN KEY (post_type_id) REFERENCES post_type (id)');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D379F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA7C2DF83 FOREIGN KEY (id_feature_image_id) REFERENCES images (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF8A43BA0 FOREIGN KEY (post_type_id) REFERENCES post_type (id)');
        $this->addSql('ALTER TABLE post_editibale ADD CONSTRAINT FK_33277CCDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post_meta ADD CONSTRAINT FK_1EA7733E9514AA5C FOREIGN KEY (id_post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_meta ADD CONSTRAINT FK_1EA7733E2BB5C43C FOREIGN KEY (acf_id) REFERENCES acf (id)');
        $this->addSql('ALTER TABLE post_modals ADD CONSTRAINT FK_D3FD7CB04B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post_modals ADD CONSTRAINT FK_D3FD7CB0AC14B70A FOREIGN KEY (modele_id) REFERENCES modeles_post (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE revision ADD CONSTRAINT FK_6D6315CC4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE permission_roles_routes ADD CONSTRAINT FK_B0E1AB2734ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permission_roles_routes ADD CONSTRAINT FK_B0E1AB2738C751C4 FOREIGN KEY (roles_id) REFERENCES roles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sidebar_menu_admin ADD CONSTRAINT FK_ADA6576DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE taxonomy ADD CONSTRAINT FK_FD12B83DC5D8F75 FOREIGN KEY (posttype_id) REFERENCES post_type (id)');
        $this->addSql('ALTER TABLE template_menu ADD CONSTRAINT FK_A8CB10FE4F992803 FOREIGN KEY (path_template_id) REFERENCES path_template_menu (id)');
        $this->addSql('ALTER TABLE terms ADD CONSTRAINT FK_88A23F718D452B01 FOREIGN KEY (id_taxonomy_id) REFERENCES taxonomy (id)');
        $this->addSql('ALTER TABLE terms ADD CONSTRAINT FK_88A23F713DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
        $this->addSql('ALTER TABLE terms_post ADD CONSTRAINT FK_42F3A65353742F27 FOREIGN KEY (terms_id) REFERENCES terms (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE terms_post ADD CONSTRAINT FK_42F3A6534B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499D30A56E FOREIGN KEY (roles_user_id) REFERENCES roles (id)');
        $this->addSql('ALTER TABLE visiteur_post ADD CONSTRAINT FK_E6DF2CC1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE visiteur_post ADD CONSTRAINT FK_E6DF2CC14B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carrousel DROP FOREIGN KEY FK_EF01B08812469DE2');
        $this->addSql('ALTER TABLE cities DROP FOREIGN KEY FK_D95DB16BF1B3F295');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC9514AA5C');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC79F37AE5');
        $this->addSql('ALTER TABLE departments DROP FOREIGN KEY FK_16AEB8D4FCE83E5F');
        $this->addSql('ALTER TABLE emplacement DROP FOREIGN KEY FK_C0CF65F6CCD7E912');
        $this->addSql('ALTER TABLE emplacement DROP FOREIGN KEY FK_C0CF65F6A76ED395');
        $this->addSql('ALTER TABLE images_post DROP FOREIGN KEY FK_60845D72D44F05E5');
        $this->addSql('ALTER TABLE images_post DROP FOREIGN KEY FK_60845D724B89032C');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A939F6F922B');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93A76ED395');
        $this->addSql('ALTER TABLE miens DROP FOREIGN KEY FK_9DC0930CA76ED395');
        $this->addSql('ALTER TABLE miens DROP FOREIGN KEY FK_9DC0930C4B89032C');
        $this->addSql('ALTER TABLE migration_log DROP FOREIGN KEY FK_399FA51EF8A43BA0');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D379F37AE5');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA7C2DF83');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DF675F31B');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DF8A43BA0');
        $this->addSql('ALTER TABLE post_editibale DROP FOREIGN KEY FK_33277CCDA76ED395');
        $this->addSql('ALTER TABLE post_meta DROP FOREIGN KEY FK_1EA7733E9514AA5C');
        $this->addSql('ALTER TABLE post_meta DROP FOREIGN KEY FK_1EA7733E2BB5C43C');
        $this->addSql('ALTER TABLE post_modals DROP FOREIGN KEY FK_D3FD7CB04B89032C');
        $this->addSql('ALTER TABLE post_modals DROP FOREIGN KEY FK_D3FD7CB0AC14B70A');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE revision DROP FOREIGN KEY FK_6D6315CC4B89032C');
        $this->addSql('ALTER TABLE permission_roles_routes DROP FOREIGN KEY FK_B0E1AB2734ECB4E6');
        $this->addSql('ALTER TABLE permission_roles_routes DROP FOREIGN KEY FK_B0E1AB2738C751C4');
        $this->addSql('ALTER TABLE sidebar_menu_admin DROP FOREIGN KEY FK_ADA6576DA76ED395');
        $this->addSql('ALTER TABLE taxonomy DROP FOREIGN KEY FK_FD12B83DC5D8F75');
        $this->addSql('ALTER TABLE template_menu DROP FOREIGN KEY FK_A8CB10FE4F992803');
        $this->addSql('ALTER TABLE terms DROP FOREIGN KEY FK_88A23F718D452B01');
        $this->addSql('ALTER TABLE terms DROP FOREIGN KEY FK_88A23F713DA5256D');
        $this->addSql('ALTER TABLE terms_post DROP FOREIGN KEY FK_42F3A65353742F27');
        $this->addSql('ALTER TABLE terms_post DROP FOREIGN KEY FK_42F3A6534B89032C');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499D30A56E');
        $this->addSql('ALTER TABLE visiteur_post DROP FOREIGN KEY FK_E6DF2CC1A76ED395');
        $this->addSql('ALTER TABLE visiteur_post DROP FOREIGN KEY FK_E6DF2CC14B89032C');
        $this->addSql('DROP TABLE acf');
        $this->addSql('DROP TABLE carrousel');
        $this->addSql('DROP TABLE category_carrousel');
        $this->addSql('DROP TABLE cities');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE configuration');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE departments');
        $this->addSql('DROP TABLE emplacement');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE images_post');
        $this->addSql('DROP TABLE mail_historique');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE miens');
        $this->addSql('DROP TABLE migration_image');
        $this->addSql('DROP TABLE migration_log');
        $this->addSql('DROP TABLE modeles_post');
        $this->addSql('DROP TABLE newsletter');
        $this->addSql('DROP TABLE notifications');
        $this->addSql('DROP TABLE options');
        $this->addSql('DROP TABLE path_template_menu');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_editibale');
        $this->addSql('DROP TABLE post_meta');
        $this->addSql('DROP TABLE post_modals');
        $this->addSql('DROP TABLE post_type');
        $this->addSql('DROP TABLE redirection');
        $this->addSql('DROP TABLE regions');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE revision');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE route');
        $this->addSql('DROP TABLE permission_roles_routes');
        $this->addSql('DROP TABLE service_tarifs');
        $this->addSql('DROP TABLE sidebar_menu_admin');
        $this->addSql('DROP TABLE sitemap');
        $this->addSql('DROP TABLE taxonomy');
        $this->addSql('DROP TABLE template_menu');
        $this->addSql('DROP TABLE terms');
        $this->addSql('DROP TABLE terms_post');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE visiteur_post');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
