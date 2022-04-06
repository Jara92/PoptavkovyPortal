<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220313200530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, identification_number VARCHAR(32) NOT NULL, tax_identification_number VARCHAR(32) DEFAULT NULL, address_street VARCHAR(64) NOT NULL, address_city VARCHAR(32) NOT NULL, address_zip_code VARCHAR(16) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company_contact (id INT AUTO_INCREMENT NOT NULL, company_name VARCHAR(64) NOT NULL, identification_number VARCHAR(16) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deadline (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(64) NOT NULL, alias VARCHAR(70) DEFAULT NULL, ordering INT NOT NULL, UNIQUE INDEX UNIQ_B74774F2E16C6B94 (alias), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inquiring_rating (id INT AUTO_INCREMENT NOT NULL, inquiry_id INT NOT NULL, supplier_id INT DEFAULT NULL, rating INT DEFAULT NULL, supplier_note LONGTEXT DEFAULT NULL, note LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_BB697764A7AD6D71 (inquiry_id), INDEX IDX_BB6977642ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inquiry (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, deadline_id INT DEFAULT NULL, value_id INT DEFAULT NULL, author_id INT DEFAULT NULL, personal_contact_id INT DEFAULT NULL, company_contact_id INT DEFAULT NULL, description LONGTEXT NOT NULL, contact_email VARCHAR(128) NOT NULL, contact_phone VARCHAR(32) DEFAULT NULL, city VARCHAR(64) DEFAULT NULL, state VARCHAR(255) NOT NULL, deadline_text VARCHAR(32) DEFAULT NULL, value_text VARCHAR(32) DEFAULT NULL, value_number INT DEFAULT NULL, type VARCHAR(255) NOT NULL, published_at DATETIME DEFAULT NULL, remove_notice_at DATETIME DEFAULT NULL, remove_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, title VARCHAR(64) NOT NULL, alias VARCHAR(70) DEFAULT NULL, hits INT DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_5A3903F0E16C6B94 (alias), INDEX IDX_5A3903F098260155 (region_id), INDEX IDX_5A3903F073EA0AF8 (deadline_id), INDEX IDX_5A3903F0F920BBA2 (value_id), INDEX IDX_5A3903F0F675F31B (author_id), UNIQUE INDEX UNIQ_5A3903F0A77182EA (personal_contact_id), UNIQUE INDEX UNIQ_5A3903F05A2FCCDC (company_contact_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inquiry_inquiry_category (inquiry_id INT NOT NULL, inquiry_category_id INT NOT NULL, INDEX IDX_B1C330ADA7AD6D71 (inquiry_id), INDEX IDX_B1C330ADE20EC513 (inquiry_category_id), PRIMARY KEY(inquiry_id, inquiry_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inquiry_attachment (id INT AUTO_INCREMENT NOT NULL, inquiry_id INT NOT NULL, hash VARCHAR(64) NOT NULL, description LONGTEXT DEFAULT NULL, size INT NOT NULL, path VARCHAR(255) NOT NULL, type VARCHAR(8) NOT NULL, title VARCHAR(64) NOT NULL, INDEX IDX_3E946C2BA7AD6D71 (inquiry_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inquiry_category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, title VARCHAR(64) NOT NULL, alias VARCHAR(70) DEFAULT NULL, UNIQUE INDEX UNIQ_A01B53C8E16C6B94 (alias), INDEX IDX_A01B53C8727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inquiry_signed_request (id INT AUTO_INCREMENT NOT NULL, inquiry_id INT NOT NULL, created_at DATETIME NOT NULL, expire_at DATETIME NOT NULL, token VARCHAR(128) NOT NULL, INDEX IDX_3264AEA8A7AD6D71 (inquiry_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inquiry_value (id INT AUTO_INCREMENT NOT NULL, value INT NOT NULL, title VARCHAR(64) NOT NULL, ordering INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, newsletter TINYINT(1) DEFAULT 1 NOT NULL, feedback TINYINT(1) DEFAULT 1 NOT NULL, UNIQUE INDEX UNIQ_BF5476CAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, inquiry_id INT NOT NULL, author_id INT NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_29D6873EA7AD6D71 (inquiry_id), INDEX IDX_29D6873EF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, surname VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personal_contact (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, surname VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT DEFAULT NULL, avatar VARCHAR(64) DEFAULT NULL, web VARCHAR(64) DEFAULT NULL, facebook VARCHAR(255) DEFAULT NULL, linkedin VARCHAR(255) DEFAULT NULL, is_public TINYINT(1) DEFAULT 1 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(64) NOT NULL, ordering INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, types JSON NOT NULL, newsletter TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription_inquiry_category (subscription_id INT NOT NULL, inquiry_category_id INT NOT NULL, INDEX IDX_D35B664C9A1887DC (subscription_id), INDEX IDX_D35B664CE20EC513 (inquiry_category_id), PRIMARY KEY(subscription_id, inquiry_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription_region (subscription_id INT NOT NULL, region_id INT NOT NULL, INDEX IDX_C694E9F19A1887DC (subscription_id), INDEX IDX_C694E9F198260155 (region_id), PRIMARY KEY(subscription_id, region_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription_inquiry (subscription_id INT NOT NULL, inquiry_id INT NOT NULL, INDEX IDX_292CE36B9A1887DC (subscription_id), INDEX IDX_292CE36BA7AD6D71 (inquiry_id), PRIMARY KEY(subscription_id, inquiry_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supplier_rating (id INT AUTO_INCREMENT NOT NULL, inquiry_id INT NOT NULL, author_id INT NOT NULL, realized_inquiry TINYINT(1) DEFAULT 0 NOT NULL, rating INT DEFAULT NULL, inquiring_note LONGTEXT DEFAULT NULL, note LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_3EBF4F6A7AD6D71 (inquiry_id), UNIQUE INDEX UNIQ_3EBF4F6F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, company_id INT DEFAULT NULL, profile_id INT NOT NULL, subscription_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, phone VARCHAR(32) DEFAULT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email_verified_at DATETIME DEFAULT NULL, last_email_verification_try DATETIME DEFAULT NULL, is_verified TINYINT(1) NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649217BBB47 (person_id), UNIQUE INDEX UNIQ_8D93D649979B1AD6 (company_id), UNIQUE INDEX UNIQ_8D93D649CCFA12B8 (profile_id), UNIQUE INDEX UNIQ_8D93D6499A1887DC (subscription_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inquiring_rating ADD CONSTRAINT FK_BB697764A7AD6D71 FOREIGN KEY (inquiry_id) REFERENCES inquiry (id)');
        $this->addSql('ALTER TABLE inquiring_rating ADD CONSTRAINT FK_BB6977642ADD6D8C FOREIGN KEY (supplier_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE inquiry ADD CONSTRAINT FK_5A3903F098260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE inquiry ADD CONSTRAINT FK_5A3903F073EA0AF8 FOREIGN KEY (deadline_id) REFERENCES deadline (id)');
        $this->addSql('ALTER TABLE inquiry ADD CONSTRAINT FK_5A3903F0F920BBA2 FOREIGN KEY (value_id) REFERENCES inquiry_value (id)');
        $this->addSql('ALTER TABLE inquiry ADD CONSTRAINT FK_5A3903F0F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE inquiry ADD CONSTRAINT FK_5A3903F0A77182EA FOREIGN KEY (personal_contact_id) REFERENCES personal_contact (id)');
        $this->addSql('ALTER TABLE inquiry ADD CONSTRAINT FK_5A3903F05A2FCCDC FOREIGN KEY (company_contact_id) REFERENCES company_contact (id)');
        $this->addSql('ALTER TABLE inquiry_inquiry_category ADD CONSTRAINT FK_B1C330ADA7AD6D71 FOREIGN KEY (inquiry_id) REFERENCES inquiry (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inquiry_inquiry_category ADD CONSTRAINT FK_B1C330ADE20EC513 FOREIGN KEY (inquiry_category_id) REFERENCES inquiry_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inquiry_attachment ADD CONSTRAINT FK_3E946C2BA7AD6D71 FOREIGN KEY (inquiry_id) REFERENCES inquiry (id)');
        $this->addSql('ALTER TABLE inquiry_category ADD CONSTRAINT FK_A01B53C8727ACA70 FOREIGN KEY (parent_id) REFERENCES inquiry_category (id)');
        $this->addSql('ALTER TABLE inquiry_signed_request ADD CONSTRAINT FK_3264AEA8A7AD6D71 FOREIGN KEY (inquiry_id) REFERENCES inquiry (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EA7AD6D71 FOREIGN KEY (inquiry_id) REFERENCES inquiry (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE subscription_inquiry_category ADD CONSTRAINT FK_D35B664C9A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscription_inquiry_category ADD CONSTRAINT FK_D35B664CE20EC513 FOREIGN KEY (inquiry_category_id) REFERENCES inquiry_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscription_region ADD CONSTRAINT FK_C694E9F19A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscription_region ADD CONSTRAINT FK_C694E9F198260155 FOREIGN KEY (region_id) REFERENCES region (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscription_inquiry ADD CONSTRAINT FK_292CE36B9A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscription_inquiry ADD CONSTRAINT FK_292CE36BA7AD6D71 FOREIGN KEY (inquiry_id) REFERENCES inquiry (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE supplier_rating ADD CONSTRAINT FK_3EBF4F6A7AD6D71 FOREIGN KEY (inquiry_id) REFERENCES inquiry (id)');
        $this->addSql('ALTER TABLE supplier_rating ADD CONSTRAINT FK_3EBF4F6F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649979B1AD6');
        $this->addSql('ALTER TABLE inquiry DROP FOREIGN KEY FK_5A3903F05A2FCCDC');
        $this->addSql('ALTER TABLE inquiry DROP FOREIGN KEY FK_5A3903F073EA0AF8');
        $this->addSql('ALTER TABLE inquiring_rating DROP FOREIGN KEY FK_BB697764A7AD6D71');
        $this->addSql('ALTER TABLE inquiry_inquiry_category DROP FOREIGN KEY FK_B1C330ADA7AD6D71');
        $this->addSql('ALTER TABLE inquiry_attachment DROP FOREIGN KEY FK_3E946C2BA7AD6D71');
        $this->addSql('ALTER TABLE inquiry_signed_request DROP FOREIGN KEY FK_3264AEA8A7AD6D71');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EA7AD6D71');
        $this->addSql('ALTER TABLE subscription_inquiry DROP FOREIGN KEY FK_292CE36BA7AD6D71');
        $this->addSql('ALTER TABLE supplier_rating DROP FOREIGN KEY FK_3EBF4F6A7AD6D71');
        $this->addSql('ALTER TABLE inquiry_inquiry_category DROP FOREIGN KEY FK_B1C330ADE20EC513');
        $this->addSql('ALTER TABLE inquiry_category DROP FOREIGN KEY FK_A01B53C8727ACA70');
        $this->addSql('ALTER TABLE subscription_inquiry_category DROP FOREIGN KEY FK_D35B664CE20EC513');
        $this->addSql('ALTER TABLE inquiry DROP FOREIGN KEY FK_5A3903F0F920BBA2');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649217BBB47');
        $this->addSql('ALTER TABLE inquiry DROP FOREIGN KEY FK_5A3903F0A77182EA');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CCFA12B8');
        $this->addSql('ALTER TABLE inquiry DROP FOREIGN KEY FK_5A3903F098260155');
        $this->addSql('ALTER TABLE subscription_region DROP FOREIGN KEY FK_C694E9F198260155');
        $this->addSql('ALTER TABLE subscription_inquiry_category DROP FOREIGN KEY FK_D35B664C9A1887DC');
        $this->addSql('ALTER TABLE subscription_region DROP FOREIGN KEY FK_C694E9F19A1887DC');
        $this->addSql('ALTER TABLE subscription_inquiry DROP FOREIGN KEY FK_292CE36B9A1887DC');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499A1887DC');
        $this->addSql('ALTER TABLE inquiring_rating DROP FOREIGN KEY FK_BB6977642ADD6D8C');
        $this->addSql('ALTER TABLE inquiry DROP FOREIGN KEY FK_5A3903F0F675F31B');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EF675F31B');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE supplier_rating DROP FOREIGN KEY FK_3EBF4F6F675F31B');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE company_contact');
        $this->addSql('DROP TABLE deadline');
        $this->addSql('DROP TABLE inquiring_rating');
        $this->addSql('DROP TABLE inquiry');
        $this->addSql('DROP TABLE inquiry_inquiry_category');
        $this->addSql('DROP TABLE inquiry_attachment');
        $this->addSql('DROP TABLE inquiry_category');
        $this->addSql('DROP TABLE inquiry_signed_request');
        $this->addSql('DROP TABLE inquiry_value');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE personal_contact');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE subscription_inquiry_category');
        $this->addSql('DROP TABLE subscription_region');
        $this->addSql('DROP TABLE subscription_inquiry');
        $this->addSql('DROP TABLE supplier_rating');
        $this->addSql('DROP TABLE user');
    }
}
