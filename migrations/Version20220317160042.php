<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220317160042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, target_id INT DEFAULT NULL, author VARCHAR(255) DEFAULT NULL, author_name VARCHAR(255) DEFAULT NULL, rating INT DEFAULT NULL, target_note LONGTEXT DEFAULT NULL, note LONGTEXT DEFAULT NULL, is_published TINYINT(1) DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, discr VARCHAR(255) NOT NULL, INDEX IDX_D8892622158E0B66 (target_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622158E0B66 FOREIGN KEY (target_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE inquiring_rating DROP FOREIGN KEY FK_BB6977642ADD6D8C');
        $this->addSql('DROP INDEX IDX_BB6977642ADD6D8C ON inquiring_rating');
        $this->addSql('ALTER TABLE inquiring_rating DROP supplier_id, DROP rating, DROP supplier_note, DROP note, DROP created_at, DROP updated_at, DROP is_published, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE inquiring_rating ADD CONSTRAINT FK_BB697764BF396750 FOREIGN KEY (id) REFERENCES rating (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE supplier_rating DROP FOREIGN KEY FK_3EBF4F6F675F31B');
        $this->addSql('DROP INDEX UNIQ_3EBF4F6F675F31B ON supplier_rating');
        $this->addSql('ALTER TABLE supplier_rating DROP author_id, DROP rating, DROP inquiring_note, DROP note, DROP created_at, DROP updated_at, DROP is_published, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE supplier_rating ADD CONSTRAINT FK_3EBF4F6BF396750 FOREIGN KEY (id) REFERENCES rating (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inquiring_rating DROP FOREIGN KEY FK_BB697764BF396750');
        $this->addSql('ALTER TABLE supplier_rating DROP FOREIGN KEY FK_3EBF4F6BF396750');
        $this->addSql('DROP TABLE rating');
        $this->addSql('ALTER TABLE inquiring_rating ADD supplier_id INT DEFAULT NULL, ADD rating INT DEFAULT NULL, ADD supplier_note LONGTEXT DEFAULT NULL, ADD note LONGTEXT DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD is_published TINYINT(1) DEFAULT 0 NOT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE inquiring_rating ADD CONSTRAINT FK_BB6977642ADD6D8C FOREIGN KEY (supplier_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_BB6977642ADD6D8C ON inquiring_rating (supplier_id)');
        $this->addSql('ALTER TABLE supplier_rating ADD author_id INT NOT NULL, ADD rating INT DEFAULT NULL, ADD inquiring_note LONGTEXT DEFAULT NULL, ADD note LONGTEXT DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD is_published TINYINT(1) DEFAULT 0 NOT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE supplier_rating ADD CONSTRAINT FK_3EBF4F6F675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3EBF4F6F675F31B ON supplier_rating (author_id)');
    }
}
